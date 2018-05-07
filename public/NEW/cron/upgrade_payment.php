<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//require_once "../public_html/config.php";
require_once "../config.php";
require_once BASEPATH . "/lib/cake.php";
require_once BASEPATH . '/includes/email.php';
require_once BASEPATH . "/lib/functions.php";


$sql = "SELECT * FROM `tbl_upgrade_payments` WHERE PayStatus = '0' ORDER BY PayProcessedDate ASC LIMIT 10";
$statement = $connection->prepare($sql);
$statement->execute();

if ($statement->rowCount() > 0) {
    $arrCheckPayment = $statement->fetchAll('assoc');
    $arrBtcAddress = array();
    $arrMapping = array();
    foreach ($arrCheckPayment as $item) {
        $arrBtcAddress[] = $item['PayAddress'];
        $arrMapping[$item['PayAddress']] = $item['PayAmount'];
        updateProcessed($connection, $item['PayID']);
    }

    // request blockchain
    $rawMultiAddress    = implode('|', $arrBtcAddress);
    $blockchainUrl      = 'https://blockchain.info/multiaddr?active='.$rawMultiAddress;
    $blockchainAddress  = grab_url_payment($blockchainUrl);
    $blockchainInfo     = json_decode($blockchainAddress, true);

    if (isset($blockchainInfo['addresses']) && !empty($blockchainInfo['addresses'])) {
        foreach ($blockchainInfo['addresses'] as $trans) {
            $checkBalance = false;
            if (isset($trans['total_received']) && $trans['total_received'] > 0) {
                $balanceTotal = $trans['total_received'] / 100000000;
                updateBalance($connection, $trans['address'], $balanceTotal);
                if ($balanceTotal >= $arrMapping[$trans['address']]) {
                    $checkBalance = true;
                }
            }

            if ($checkBalance) {

                $processPayment = processPayment($connection, $trans['address']);

                if ($processPayment) {

                    // payment
                    $sql = "SELECT * FROM `tbl_upgrade_payments` WHERE `PayAddress` = :PayAddress";
                    $statement = $connection->prepare($sql);
                    $statement->bind(['PayAddress' => $trans['address']], ['PayAddress' => 'string']);
                    $statement->execute();
                    $payment = $statement->fetch('assoc');
                    $projectID = $payment['ProjID'];

                    // project
                    $sql = "SELECT * FROM `tbl_projects` WHERE `ProjID` = :ProjID";
                    $statement = $connection->prepare($sql);
                    $statement->bind(['ProjID' => $projectID], ['ProjID' => 'integer']);
                    $statement->execute();
                    $project = $statement->fetch('assoc');

                    $email_subject = "Upgrade to ".$project['ProjName']." done";
                    $email_message = "";
                    $email_message .= "ProjID: ".$project['ProjID']."\n";
                    $email_message .= "Project Name: ".$project['ProjName']."\n";
                    $email_message .= "Payment ID: ".$payment['PayID']."\n";
                    $email_message .= "Package: ".$payment['PayPackage']."\n";
                    $email_message .= "Address: ".$payment['PayAddress']."\n";

                    send_mail("moon@coinschedule.com","moon@coinschedule.com",$email_subject,$email_message);
                }
            }
        }
    }
}

function updateProcessed($connection, $payID)
{
    $connection->update('tbl_upgrade_payments', array('PayProcessedDate' => date('Y-m-d H:i:s')), array('PayID' => $payID));
}

function updateBalance($connection, $address, $balanceTotal)
{
    $sql = "SELECT * FROM `tbl_upgrade_payments` WHERE `PayAddress` = :PayAddress";
    $statement = $connection->prepare($sql);
    $statement->bind(['PayAddress' => $address], ['PayAddress' => 'string']);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $payment = $statement->fetch('assoc');
        $connection->update('tbl_upgrade_payments', array('PayBalance' => $balanceTotal), array('PayID' => $payment['PayID']));
    }

}

function processPayment($connection, $address)
{
    /**
     * Gold = set ProjSponsored flag to 1 (in tbl_projects) and also set ProjPackage to 2
     * Silver = leave ProjSponsored at 0 and set ProjPackage to 1
     * Platinum = set ProjSponsored to 1, ProjPackage to 2 and ProjPlatinum to 1
     */

    $sql = "SELECT * FROM `tbl_upgrade_payments` WHERE `PayAddress` = :PayAddress";
    $statement = $connection->prepare($sql);
    $statement->bind(['PayAddress' => $address], ['PayAddress' => 'string']);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $payment = $statement->fetch('assoc');

        if ($payment['PayPackage'] == 'platinum') {
            $ProjSponsored = 1;
            $ProjPackage = 2;
            $ProjPlatinum = 1;
        } else if ($payment['PayPackage'] == 'gold') {
            $ProjSponsored = 1;
            $ProjPackage = 2;
            $ProjPlatinum = 0;
        } else {
            $ProjSponsored = 0;
            $ProjPackage = 1;
            $ProjPlatinum = 0;
        }

        $arrProjectField = array(
            'ProjSponsored' => $ProjSponsored,
            'ProjPackage' => $ProjPackage,
            'ProjPlatinum' => $ProjPlatinum,
        );
        saveProjectLog($connection, $arrProjectField, $payment['ProjID'], true);

        $connection->update('tbl_projects',
            $arrProjectField,
            array('ProjID' => $payment['ProjID'])
        );

        $connection->update('tbl_upgrade_payments',
            array('PayStatus' => '1', 'PayDatetime' => date('Y-m-d H:i:s')),
            array('PayAddress' => $address)
        );

        return true;
    }

    return false;
}