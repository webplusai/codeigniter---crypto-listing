<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../public_html/config.php";
//require_once "../config.php";
require_once BASEPATH . "/lib/cake.php";
require_once BASEPATH . "/lib/php-image-resize/lib/ImageResize.php";
require_once BASEPATH . '/includes/email.php';
require_once BASEPATH . "/lib/functions.php";

$listingFee = getSetting($connection, 2);
define("MY_AMOUNT", $listingFee);
$sql = "Select S.SubID,SubStatus,SubCoinName,SubHashCode,PaySecret,SubProjID,P.PayAddress,P.PayAmount from tbl_submissions S left join tbl_payments P on S.SubID = P.SubID Where SubStatus = 1 and SubCoinName <> '' ORDER BY SubDate DESC LIMIT 50";
$statement = $connection->prepare($sql);
$statement->execute();

if ($statement->rowCount() > 0) {
    $arrCheckPayment = $statement->fetchAll('assoc');
    $arrBtcAddress = array();
    $arrMapping = array();
    foreach ($arrCheckPayment as $item) {
        $arrBtcAddress[] = $item['PayAddress'];
        $arrMapping[$item['PayAddress']] = $item['PayAmount'];
    }

    // request blockchain
    $rawMultiAddress    = implode('|', $arrBtcAddress);
    $blockchainUrl      = 'https://blockchain.info/multiaddr?active='.$rawMultiAddress;
    $blockchainAddress  = grab_url_payment($blockchainUrl);
    $blockchainInfo     = json_decode($blockchainAddress, true);

    if (!empty($blockchainInfo)) {
        foreach ($blockchainInfo['addresses'] as $trans) {
            $checkBalance = false;
            if ($trans['total_received'] > 0) {
                $balanceTotal = $trans['total_received'] / 100000000;
                updateBalance($connection, $trans['address'], $balanceTotal);
                if ($balanceTotal >= $arrMapping[$trans['address']]) {
                    $checkBalance = true;
                }
            }

            if ($checkBalance) {
                $sql = "SELECT * FROM `tbl_payments` WHERE `PayAddress` = :PayAddress";
                $statement = $connection->prepare($sql);
                $statement->bind(['PayAddress' => $trans['address']], ['PayAddress' => 'string']);
                $statement->execute();
                $paymentRecord = $statement->fetch('assoc');

                $sql = "SELECT * FROM `tbl_submissions` WHERE `SubID` = :SubID";
                $statement = $connection->prepare($sql);
                $statement->bind(['SubID' => $paymentRecord['SubID']], ['SubID' => 'integer']);
                $statement->execute();
                $subRecord = $statement->fetch('assoc');

                $processPayment = processPayment($connection, $paymentRecord['PaySecret'], $subRecord);
                if ($processPayment) {

                    $email_subject = "Your Coinschedule Submission";
                    $email_message = "Dear ".$subRecord['SubName'].",\n\n";
                    $email_message .= "Your submission has been received and your payment has been confirmed, so your listing has been automatically added to our main database.\n\n";
                    $email_message .= "In order to appear on the website, your project needs a minumum ICOrank. Our system will assign an ICOrank to your project in the next few minutes based on the information you provided. If your ICOrank is above our minimum threshold, it will show up on the Coinschedule home page automatically. If not, you will be able to edit your entry and add more information so it reaches the minimum threshold. \n\n";
                    $email_message .= "You can check your listing's ICOrank by logging into your account and going to the 'Listing' menu item. In case your are interested in upgrading your listing to a paid one, or in other marketing options we provide, please check our packages here: https://www.coinschedule.com/packages.php\n\n";
                    $email_message .= "Feel free to contact us in case you have any questions.\n\n";
                    $email_message .= "All the best and good luck with your project!\n\n";
                    $email_message .= "Coinschedule Team\n";

                    send_mail($subRecord['SubEmail'],"moon@coinschedule.com",$email_subject,$email_message);


                    $email_message = "";
                    $email_message .= "ProjID: ".$subRecord['SubProjID']."\n";
                    $email_message .= "Project Name: ".$subRecord['SubCoinName']."\n";
                    if ($subRecord['SubDatesNotDefined']) {
                        $email_message .= "Start Date: ".date("Y-m-d", strtotime($subRecord['SubStart']))."\n";
                        $email_message .= "End Date: ".date("Y-m-d", strtotime($subRecord['SubEnd']))."\n";
                    } else {
                        $email_message .= "Dates not defined yet - TBA \n";
                    }

                    send_mail('moon@coinschedule.com',"moon@coinschedule.com",'New basic project paid',$email_message);
                }
            }
        }
    }
}

function updateBalance($connection, $address, $balanceTotal)
{
    $sql = "SELECT * FROM `tbl_payments` WHERE `PayAddress` = :PayAddress";
    $statement = $connection->prepare($sql);
    $statement->bind(['PayAddress' => $address], ['PayAddress' => 'string']);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $payment = $statement->fetch('assoc');
        $connection->update('tbl_payments', array('PayBalance' => $balanceTotal), array('PayID' => $payment['PayID']));
    }

}

function processPayment($db, $secret, $sub)
{
    $statement = $db->prepare("SELECT * FROM `tbl_payments` WHERE `SubID` = :SubID AND `PayStatus` = '0' AND `PaySecret` = :PaySecret");
    $statement->bind(['SubID' => $sub['SubID']], ['SubID' => 'integer']);
    $statement->bind(['PaySecret' => $secret], ['PaySecret' => 'string']);
    $statement->execute();

    if ($statement->rowCount() > 0 ) {
        $arrUpdate = array(
            'PayStatus' => 1,
            'PayDatetime' => date('Y-m-d H:i:s')
        );
        $db->update('tbl_payments', $arrUpdate, array('SubID' => $sub['SubID']));

        // update SubStatus
        $arrUpdate = array(
            'SubStatus' => 2, //Approved
            'SubStatusUpdatedOn' => date('Y-m-d H:i:s')
        );
        $db->update('tbl_submissions', $arrUpdate, array('SubID' => $sub['SubID']));


        $statement = $db->prepare("Select * from tbl_submissions where (SubProjID IS NULL OR SubProjID = 0) AND SubID = :SubID");
        $statement->bind(['SubID' => $sub['SubID']], ['SubID' => 'integer']);
        $statement->execute();

        if ($statement->rowCount() > 0 ) {

            // Update tbl_projects
            $ProjImage = '';
            $ProjImageLarge = '';

            $arrInsertPrj = array(
                'ProjCatID' => $sub['SubProjCatID'],
                'ProjUsers' => $sub['SubUsers'],
                'ProjType' => $sub['SubProjType'],
                'ProjPlatform' => $sub['SubPlatform'],
                'ProjDesc' => $sub['SubInfo'],
                'ProjName' => $sub['SubCoinName'],
                'ProjSymbol' => $sub['SubSymbol'],
                'ProjTotalSupp' => $sub['SubSupply'],
                'ProjImage' => $ProjImage,
                'ProjImageLarge' => $ProjImageLarge,
                'ProjAddedOn' => date('Y-m-d H:i:s')
            );
            $projectInsert = $db->insert('tbl_projects', $arrInsertPrj);

            $ProjID = $projectInsert->lastInsertId();
            if ($ProjID) {
                // Update tbl_submissions
                $arrUpdate = array('SubProjID' => $ProjID);
                $db->update('tbl_submissions', $arrUpdate, array('SubID' => $sub['SubID']));

                // Update tbl_events
                $EventName = $sub['SubEventName'];
                $EventStartDate = '';
                $EventEndDate = '';
                $EventStartDateType = 1;
                if ($sub['SubDatesNotDefined']) {
                    $EventStartDateType = 3;
                } else {
                    $EventStartDate = date("Y-m-d H:i:s", strtotime($sub['SubStart']));
                    $EventEndDate = date("Y-m-d H:i:s", strtotime($sub['SubEnd']));
                }

                $arrInsert = array(
                    'EventProjID' => $ProjID,
                    'EventName' => $EventName,
                    'EventStartDate' => $EventStartDate,
                    'EventEndDate' => $EventEndDate,
                    'EventStartDateType' => $EventStartDateType
                );
                $eventsInsert = $db->insert('tbl_events', $arrInsert);

                // Update tbl_links
                $mappingLink = array(
                    1 => 'SubLink',
                    14 => 'SubWhitePaper',
                    5 => 'SubTwitter',
                    6 => 'SubReddit',
                    9 => 'SubSlack',
                    4 => 'SubBitcoinTalk'
                );

                foreach ($mappingLink as $key => $value) {
                    if (!empty($sub[$value])) {
                        $LinkType = $key;
                        $LinkParentType = 1;
                        $Link = $sub[$value];

                        $arrInsert = array(
                            'LinkType' => $LinkType,
                            'LinkParentType' => $LinkParentType,
                            'LinkParentID' => $ProjID,
                            'Link' => $Link
                        );
                        $linkInsert = $db->insert('tbl_links', $arrInsert);
                    }
                }

                $arrInsertPrj['ProjID'] = $ProjID;
                $arrInsertPrj['EventName'] = $EventName;
                $arrInsertPrj['EventStartDate'] = $EventStartDate;
                $arrInsertPrj['EventEndDate'] = $EventEndDate;
                $arrInsertPrj['EventStartDateType'] = $EventStartDateType;
                saveProjectLog($db, $arrInsertPrj);




                if ($sub['SubLogo']) {
                    $filename = BASEPATH . '/' . 'uploads/logo/'.$sub['SubLogo'];
                    $grabImage = true;
                } else {
                    $filename = BASEPATH . '/' . 'uploads/logo/'.$sub['SubID'].rand(100, 999).time().'.jpg';
                    $grabImage = grab_image($sub['SubLogoLink'], $filename);
                }

                if ($grabImage && file_exists($filename)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $fileinfo = finfo_file($finfo, $filename);
                    if (strpos($fileinfo, 'image') !== FALSE) {

                        $image = new \Eventviva\ImageResize($filename);
                        $image->crop(16, 16);
                        $resizePath = pathinfo($filename, PATHINFO_DIRNAME).'/'.pathinfo($filename, PATHINFO_FILENAME).'16x16.'.pathinfo($filename, PATHINFO_EXTENSION);
                        $image->save($resizePath);
                        if (file_get_contents($resizePath)) {
                            $ProjImage = base64_encode(file_get_contents($resizePath));
                            unlink($resizePath);
                        }

                        $image = new \Eventviva\ImageResize($filename);
                        $image->crop(48, 48);
                        $resizePath = pathinfo($filename, PATHINFO_DIRNAME).'/'.pathinfo($filename, PATHINFO_FILENAME).'48x48.'.pathinfo($filename, PATHINFO_EXTENSION);
                        $image->save($resizePath);
                        if (file_get_contents($resizePath)) {
                            $ProjImageLarge = base64_encode(file_get_contents($resizePath));
                            unlink($resizePath);
                        }

                        unlink($filename);
                    }
                }

                $arrUpdate = array(
                    'ProjImage' => $ProjImage,
                    'ProjImageLarge' => $ProjImageLarge,
                );
                $db->update('tbl_projects', $arrUpdate, array('ProjID' => $ProjID));
            }
        }

        return true;
    }

    return false;
}

function grab_image($url, $saveto) {
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw=curl_exec($ch);
    curl_close ($ch);

    if(file_exists($saveto)){
        unlink($saveto);
    }

    if ($raw) {
        $fp = fopen($saveto,'x');
        fwrite($fp, $raw);
        fclose($fp);
        chmod($saveto, 0777);
        return true;
    }
    return false;
}