<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/core/MY_Controller.php';

use BlockCypher\Auth\SimpleTokenCredential;
use BlockCypher\Rest\ApiContext;
use BlockCypher\Client\PaymentForwardClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;


class Cron extends MY_Controller
{
    public $cronId = 0;
    const PAY_APPROX = 0.001;

    public function __construct()
    {
        parent::__construct(true);
        $this->load->helper('common');

        $this->load->model('Cron_model', 'CronModel');
        $this->cronId = $this->CronModel->insert([
            'name' => $this->router->fetch_method()
        ]);
    }

    public function btcToUsd()
    {
        try {
            $btcPrice = json_decode(grab_url("https://api.coinmarketcap.com/v1/ticker/bitcoin/"),true);
            if (isset($btcPrice[0]['price_usd'])) {
                echo $btcPrice[0]['price_usd'];
                $this->updateCronStatus($btcPrice[0]['price_usd']);
                $this->load->model('Setting_model', 'SettingModel');
                $this->SettingModel->update(['SettingValue' => $btcPrice[0]['price_usd']], ['SettingID' => 8]);
            }
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
        }
    }

    public function pushPayment()
    {
        try {
            $this->load->model('Payment_model', 'PaymentModel');

            if (!isset($_GET['h'])) {
                echo 'Please set hash code';
                $this->updateCronStatus('Please set hash code');
                return false;
            }

            $payment = $this->PaymentModel->getPushPayment($_GET['h']);
            if (empty($payment)) {
                echo 'Invalid hash code';
                $this->updateCronStatus('Invalid hash code ' . $_GET['h']);
                return true;
            }

            $this->updateCronStatus('OK');
            $this->payment($payment);
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
        }
    }

    public function payment($payment = false)
    {
        try {
            $this->load->model('Payment_model', 'PaymentModel');
            $this->load->model('Submission_model', 'SubmissionModel');
            $this->load->model('Project_model', 'ProjectModel');

            if ($payment === false) {
                $payment = $this->PaymentModel->getPaymentCronProcess();
            }

            if (empty($payment)) {
                $this->updateCronStatus('Empty list');
                return true;
            }

            // request blockchain
            $arrMapping = array();
            $addressList = array();
            foreach ($payment as $item) {
                $addressList[] = $item['PayAddress'];
                $arrMapping[$item['PayAddress']] = $item['PayAmount'];
            }
            $rawMultiAddress    = implode('|', $addressList);
            $blockchainUrl      = 'https://blockchain.info/multiaddr?active='.$rawMultiAddress;
            $blockchainAddress  = grab_url_payment($blockchainUrl);
            $blockchainInfo     = json_decode($blockchainAddress, true);
            if (empty($blockchainInfo)) {
                $this->updateCronStatus('Empty blockchain info');
                return true;
            }

            $isCleanCache = false;
            foreach ($blockchainInfo['addresses'] as $trans) {
                $checkBalance = false;
                if ($trans['total_received'] > 0) {
                    $balanceTotal = $trans['total_received'] / 100000000;
                    $this->PaymentModel->updateBalance($trans['address'], $balanceTotal);

                    $extraBalanceTotal = $balanceTotal + self::PAY_APPROX;
                    if ($extraBalanceTotal >= $arrMapping[$trans['address']]) {
                        $checkBalance = true;
                    }
                } else if ($arrMapping[$trans['address']] == 0) {
                    $checkBalance = true;
                }

                if ($checkBalance) {
                    $paymentRecord = $this->PaymentModel->getPaymentByAddress($trans['address'], TRUE);
                    $subRecord = $this->SubmissionModel->getSubmissionById($paymentRecord['SubID']);
                    $processPayment = $this->processPayment($subRecord);

                    if ($processPayment) {
                        $isCleanCache = true;
                        $this->load->helper('email');
                        $subRecord = $this->SubmissionModel->getSubmissionById($paymentRecord['SubID']);

                        $mailBody = $this->load->view('emails/payment_submission', $subRecord, true);
                        $sendFrom = array('email' => $this->config->config['send_from'], 'name' => $this->config->config['send_from_name']);
                        $sendTo = array('email' => $subRecord['SubEmail'], 'name' => '');
                        $subject = 'Your Coinschedule Submission';
                        send_email($sendFrom, $sendTo, $subject, $mailBody);

                        $mailBody = $this->load->view('emails/payment_submission_admin', $subRecord, true);
                        $sendFrom = array('email' => $this->config->config['send_from'], 'name' => $this->config->config['send_from_name']);
                        $sendTo = array('email' => $this->config->config['admin_email'], 'name' => '');
                        $subject = 'New basic project paid';
                        send_email($sendFrom, $sendTo, $subject, $mailBody);
                    }
                }
            }

            if ($isCleanCache) {
                $this->setIcoRank();
                $this->cache->redis->delete('cachedDataHomepage');
                $this->cache->redis->delete('cachedICOData');
                $this->updateCronStatus('clear cache');
            } else {
                $this->updateCronStatus('OK');
            }

        } catch (\Exception $e) {
            $this->updateCronStatus($e);
            log_message('error', 'Cron: '.$e->getMessage());
        }
    }

    public function payment1000()
    {
        try {
            $this->load->model('Payment_model', 'PaymentModel');
            $this->load->model('Submission_model', 'SubmissionModel');
            $this->load->model('Project_model', 'ProjectModel');

            $cronData = $this->PaymentModel->getPaymentCronProcess1000();
            if (empty($cronData)) {
                $this->updateCronStatus('Empty list');
                return true;
            }
            $chunkCronData = array_chunk($cronData, 100, true);

            foreach ($chunkCronData as $payment) {
                $this->payment($payment);
                sleep(10);
            }

        } catch (\Exception $e) {
            $this->updateCronStatus($e);
            log_message('error', 'Cron: '.$e->getMessage());
        }
    }

    public function upgradePayment()
    {
        try {
            $this->load->model('Upgrade_payment_model', 'UpgradePaymentModel');
            $this->load->model('Project_model', 'ProjectModel');
            $upgradeList = $this->UpgradePaymentModel->getCronList();
            if (empty($upgradeList)) {
                $this->updateCronStatus('Empty list');
                return true;
            }

            $arrMapping = [];
            $arrBtcAddress = [];
            foreach ($upgradeList as $item) {
                $arrBtcAddress[] = $item['PayAddress'];
                $arrMapping[$item['PayAddress']] = $item['PayAmount'];
                $this->UpgradePaymentModel->update(array('PayProcessedDate' => date('Y-m-d H:i:s')), array('PayID' => $item['PayID']));
            }

            // request blockchain
            $rawMultiAddress    = implode('|', $arrBtcAddress);
            $blockchainUrl      = 'https://blockchain.info/multiaddr?active='.$rawMultiAddress;
            $blockchainAddress  = grab_url_payment($blockchainUrl);
            $blockchainInfo     = json_decode($blockchainAddress, true);

            if (!isset($blockchainInfo['addresses']) || empty($blockchainInfo['addresses'])) {
                $this->updateCronStatus('Empty blockchain info');
                return true;
            }

            foreach ($blockchainInfo['addresses'] as $trans) {
                $checkBalance = false;
                if (isset($trans['total_received']) && $trans['total_received'] > 0) {
                    $balanceTotal = $trans['total_received'] / 100000000;
                    $this->UpgradePaymentModel->updateBalance($trans['address'], $balanceTotal);

                    $extraBalanceTotal = $balanceTotal + self::PAY_APPROX;
                    if ($extraBalanceTotal >= $arrMapping[$trans['address']]) {
                        $checkBalance = true;
                    }
                }

                if ($checkBalance) {

                    $processPayment = $this->processUpgradePayment($trans['address']);

                    if ($processPayment) {

                        // payment
                        $payment = $this->UpgradePaymentModel->getPaymentByAddress($trans['address']);
                        $projectID = $payment['ProjID'];

                        // project
                        $project = $this->ProjectModel->getProjectByID($projectID);

                        $this->load->helper('email');
                        $data = [
                            'project' => $project,
                            'payment' => $payment,
                        ];
                        $mailBody = $this->load->view('emails/upgrade_payment', $data, true);
                        $sendFrom = array('email' => $this->config->config['send_from'], 'name' => $this->config->config['send_from_name']);
                        $sendTo = array('email' => $this->config->config['admin_email'], 'name' => '');
                        $subject = "Upgrade to ".$project['ProjName']." done";
                        send_email($sendFrom, $sendTo, $subject, $mailBody);

                    }
                }
            }

            $this->updateCronStatus('OK');
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
            log_message('error', 'Cron: '.$e->getMessage());
        }
    }

    public function sendMailSubmit()
    {
        try {
            $this->load->model('Submission_model', 'SubmissionModel');
            $this->load->helper('email');
            $subList = $this->SubmissionModel->sendMailSubmit();
            if (empty($subList)) {
                return true;
            }

            foreach ($subList as $sub) {
                $arrUpdate = array('SubSendMail' => 1);
                $this->SubmissionModel->update($arrUpdate, array('SubID' => $sub['SubID']));

                $mailBody = $this->load->view('emails/submission_started', $sub, true);
                $sendFrom = array('email' => $this->config->config['send_from'], 'name' => $this->config->config['send_from_name']);
                $sendTo = array('email' => $sub['SubEmail'], 'name' => '');
                $subject = 'Submission process started';
                send_email($sendFrom, $sendTo, $subject, $mailBody);
            }

            $this->updateCronStatus('OK');
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
            log_message('error', 'Cron: '.$e->getMessage());
        }
    }

    public function cleanAllCache()
    {
        $this->cache->redis->clean();
        $this->load->model('Setting_model', 'SettingModel');
        $this->updateCronStatus('OK');
        $this->SettingModel->updateLastClearedCache();
        header("Location: ".base_url()."change/siteadmin.php?mode=2");
        exit();
    }

    public function cleanCacheLastDB()
    {
        try {
            $this->load->model('Setting_model', 'SettingModel');
            $isClean = $this->SettingModel->cronCheckCleanCache();
            var_dump($isClean);
            $this->updateCronStatus($isClean);
            if ($isClean) {
                $this->cache->redis->clean();
                $this->load->model('Setting_model', 'SettingModel');
                $this->SettingModel->updateLastClearedCache();
            }
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
        }
    }

    public function clearTwigCache()
    {
        $this->load->helper('file');
        delete_files(FCPATH . 'application/compilation_cache/');
        header("Location: ".base_url()."change/siteadmin.php?mode=1");
        exit();
    }

    public function updateLastestPost()
    {
        $url = "https://www.coinschedule.com/blog/wp-json/wp/v2/posts/?per_page=10&_embed&categories_exclude=50";
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);
    	$output = curl_exec($ch);    
        
        $fp = fopen(FCPATH. "public/blogs.json", 'w');
        fwrite($fp, $output);
        fclose($fp);

        curl_close($ch);

        $this->cache->redis->delete('cachedBlogPost');
    }

    public function scrapeEvent()
    {
        try {
            $this->load->model('Event_site_model', 'EventSiteModel');
            $this->load->model('Event_model', 'EventModel');
            $scrapes = $this->EventSiteModel->getScrapeEvent();
            if (empty($scrapes)) {
                $this->updateCronStatus('empty scrapes');
                return false;
            }

            $this->updateCronStatus('OK');
            foreach ($scrapes as $scrape) {
                $siteURL = $scrape['EventSiteURL'];
                $siteURL = substr($siteURL, -1) == '/'?substr($siteURL,0,strlen($siteURL)-1):$siteURL;
                $eventDOM = getUrlasDOMXPath($siteURL);

                if (!empty($eventDOM)) {
                    $event = $eventDOM->query($scrape['EventSiteScrapeDomQuery']);
                    if ($event->length > 0) {
                        foreach ($event as $article) {
                            // Name
                            $name = '';
                            $eventname = $eventDOM->query($scrape['EventSiteScrapeNameDomQuery'],$article);
                            if($eventname->length > 0) {
                                if ($scrape['EventSiteScrapeNameDomQueryItemAttrib']=='nodeValue') {
                                    $name = $eventname->item($scrape['EventSiteScrapeNameDomQueryItem'])->nodeValue;
                                } else {
                                    $name = $eventname->item($scrape['EventSiteScrapeNameDomQueryItem'])->getAttribute($scrape['EventSiteScrapeNameDomQueryItemAttrib']);
                                }
                            }
                            $name = trim($name);

                            // Link
                            $link = '';
                            $eventlink = $eventDOM->query($scrape['EventSiteScrapeLinkDomQuery'],$article);
                            if($eventlink->length > 0) {
                                if ($scrape['EventSiteScrapeLinkDomQueryItemAttrib']=='nodeValue') {
                                    $link = $eventlink->item($scrape['EventSiteScrapeLinkDomQueryItem'])->nodeValue;
                                } else {
                                    $link = $eventlink->item($scrape['EventSiteScrapeLinkDomQueryItem'])->getAttribute($scrape['EventSiteScrapeLinkDomQueryItemAttrib']);
                                }
                            }
                            $link = trim($link);
                            if(substr($link,0,1)=='/') {
                                $link = trim($siteURL.$link);
                            }


                            $countEventWebsite = $this->EventSiteModel->countEventWebsite($link, $name);
                            if($countEventWebsite['Total'] == 0 && $name != '' && $link != '') {

                                // Image
                                $image = "";
                                $imagefile = grab_url("http://www.google.com/s2/favicons?domain_url=$link");
                                $image = base64_encode($imagefile);

                                // Date
                                $enddate = "";
                                $eventdate = $eventDOM->query($scrape['EventSiteScrapeDateDomQuery'],$article);

                                if($eventdate->length > 0) {
                                    if ($scrape['EventSiteScrapeDateDomQueryItemAttrib']=='nodeValue') {
                                        $date = $eventdate->item($scrape['EventSiteDateScrapeDomQueryItem'])->nodeValue;
                                    } else {
                                        $date = $eventdate->item($scrape['EventSiteScrapeDateDomQueryItem'])->getAttribute($scrape['EventSiteScrapeDomDateQueryItemAttrib']);
                                    }

                                    if(strpos($date,"-")!==false) {
                                        $enddate = $date;
                                        $arr = explode(' ',trim($enddate));
                                        $enddate = $arr[0].' '.substr($enddate,strpos($enddate,"-")+1);
                                        $enddate = date("Y-m-d",strtotime($enddate));

                                        $startdate = substr($date,0,strpos($date,"-")).' '.$arr[2];
                                        $date = date("Y-m-d",strtotime($startdate));
                                    } else {
                                        $date = date("Y-m-d",strtotime($date));
                                    }
                                }


                                // Location
                                $location = '';
                                $eventlocation = $eventDOM->query($scrape['EventSiteScrapeLocationDomQuery'],$article);
                                if($eventlocation->length > 0) {
                                    if ($scrape['EventSiteScrapeLocationDomQueryItemAttrib']=='nodeValue') {
                                        $location = $eventlocation->item($scrape['EventSiteScrapeLocationDomQueryItem'])->nodeValue;
                                    } else
                                    {
                                        $location = $eventlocation->item($scrape['EventSiteScrapeLocationDomQueryItem'])->getAttribute($scrape['EventSiteScrapeDomLocationQueryItemAttrib']);
                                    }
                                }
                                $location = trim($location);


                                $insertData = [
                                    'EventName' => $name,
                                    'EventStartDate' => $date,
                                    'EventEndDate' => '',
                                    'EventWebsite' => $link,
                                    'EventLocation' => $location,
                                    'EventImage' => $image,
                                    'EventType' => 2,
                                ];
                                if ($enddate) {
                                    $insertData['EventEndDate'] = $enddate;
                                }

                                $this->EventModel->insert($insertData);

                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
            log_message('error', 'Cron: '.$e->getMessage());
        }
    }

    public function icoTotal($plat)
    {
        try {
            $platWaves = FALSE;
            if ($plat=='waves') {
                $platWaves = TRUE;
            }

            $this->load->model('Cron_model', 'CronModel');
            $liveicostotal = '';
            $liveicos = $this->CronModel->getIcoTotalLive($platWaves);
            if (isset($liveicos['Total'])) {
                $liveicostotal = $liveicos['Total'];
            }

            $ucicostotal = '';
            $ucicos = $this->CronModel->getUCTotalLive($platWaves);
            if (isset($ucicos['Total'])) {
                $ucicostotal = $ucicos['Total'];
            }

            $totals['upcoming'] = $ucicostotal;
            $totals['live'] = $liveicostotal;
            $totals = json_encode($totals);
            $this->load->helper('file');
            write_file(FCPATH . "widget/icototals$plat.json", $totals, 'w');
            $this->updateCronStatus('OK');
        } catch (\Exception $e) {
            log_message('error', 'Cron: '.$e->getMessage());
            $this->updateCronStatus($e);
            return false;
        }
    }

    public function setIcoRank()
    {
        try {
            $this->load->model('Cron_model', 'CronModel');
            $this->load->model('Project_model', 'ProjectModel');
            $projects = $this->CronModel->getCronIcoRank();
            if (empty($projects)) {
                return FALSE;
            }

            echo "<table><tr><td>ID</td><td>Project</td><td>ICOrank</td></tr>";
            foreach ($projects as $project) {
                $ICOrank=0; //initialise variable

                //get all links of this project
                $links_st = $this->CronModel->getProjectLinks($project['ProjID']);
                foreach ($links_st as $links) {
                    //if it's a website link give 7 points (type 1)
                    if ($links['LinkType']==1)$ICOrank=$ICOrank+7;

                    //if it's a PDF white paper give 10 points (type 14)
                    if ($links['LinkType']==14)$ICOrank=$ICOrank+10;

                    //if it's Twitter give 2 points (type 5)
                    if ($links['LinkType']==5)$ICOrank=$ICOrank+2;

                    //if it's reddit give 2 points (type 6)
                    if ($links['LinkType']==6)$ICOrank=$ICOrank+2;

                    //if it's slack give 5 points (type 9)
                    if ($links['LinkType']==9)$ICOrank=$ICOrank+6;

                    //if it's bitcointalk give 5 points (type 4)
                    if ($links['LinkType']==4)$ICOrank=$ICOrank+5;
                }


                //get all team members of this project
                $team_st = $this->CronModel->getProjectPeople($project['ProjID']);
                if (!empty($team_st)) {
                    //if it's 1 team member give 4 points if 2 or more give 10 points
                    if(count($team_st)==1)$ICOrank=$ICOrank+5;
                    if(count($team_st)>=1)$ICOrank=$ICOrank+9;

                    //if it's 1 linkedin 2 points if 2 or more give 5 points
                    $links2 = $this->CronModel->getProjectLinksSt($project['ProjID']);
                    if (!empty($links2)){
                        if(count($links2)==1)$ICOrank=$ICOrank+3;
                        if(count($links2)>=1)$ICOrank=$ICOrank+5;
                    }

                    //if it's 1 uploaded ID give 12 points if more give 18 points
                    //*** THIS IS NOT IMPLEMENTED YET ***

                }


                //get editorial grade of this project and assign to ICOrank
                $ICOrank=$ICOrank+$project['ProjEditorialGrade'];

                //get paid level of this project
                if ($project['ProjSponsored']==1){
                    //if project is SILVER, give 6 points
                    //**** SILVER DOES NOT EXIST IN COINSCHEDULE YET

                    //if project is GOLD, give 14 points
                    //AT the moment, non platinum means is gold
                    if ($project['ProjPlatinum']==0)$ICOrank=$ICOrank+11;

                    //if project is PLATINUM give 22 points
                    if ($project['ProjPlatinum']==1)$ICOrank=$ICOrank+17;

                }


                echo "<tr>";
                echo "<td>".$project['ProjID']."</td>";
                echo "<td>".$project['ProjName']."</td>";
                echo "<td>";
                if($ICOrank<10)$color="red";
                if($ICOrank<20)$color="orange";
                if($ICOrank>=20)$color="green";
                echo "<span style=color:$color>".$ICOrank."</span>";
                echo "</td>";
                echo "</tr>";

                //record in DB
                $this->ProjectModel->update(['ProjICORank' => $ICOrank], ['ProjID' => $project['ProjID']]);
            }

            echo "</table>";
            $this->updateCronStatus('OK');
        } catch (\Exception $e) {
            log_message('error', 'Cron: '.$e->getMessage());
            $this->updateCronStatus($e);
            return false;
        }
    }

    public function setFilterPanel()
    {
        try {
            $this->load->model('Cron_model', 'CronModel');
            $this->CronModel->setFilterPanel();
            $this->updateCronStatus('OK');
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
            log_message('error', 'Cron: '.$e->getMessage());
            return false;
        }
    }

    public function scrapePressMention()
    {
        try {
            $this->load->model('Press_model', 'PressModel');
            $pressDOM = getUrlasDOMXPath("https://www.google.co.uk/search?biw=1920&bih=1109&tbm=nws&q=coinschedule&oq=coinschedule");

            if(!empty($pressDOM))
            {
                $this->updateCronStatus('OK');
                $press = $pressDOM->query("//div[@class='g']");

                if($press->length > 0)
                {
                    foreach($press as $mention)
                    {
                        $mention = $pressDOM->query("div",$mention);

                        $name = $mention->item(0)->getElementsByTagName('span')->item(0)->nodeValue;

                        $date = $mention->item(0)->getElementsByTagName('span')->item(2)->nodeValue;
                        $date = date_format(date_create($date),"Y-m-d");
                        $headline = $mention->item(0)->getElementsByTagName('a')->item(1)->nodeValue;
                        $link = $mention->item(0)->getElementsByTagName('a')->item(0)->getAttribute('href');


                        if ($name && $date && $link && $headline && $name != '' && $headline != '' && $link != '')
                        {
                            $pressLink = $this->PressModel->getData(['PressLink' => $link]);
                            if (empty($pressLink)) {
                                $insertData = [
                                    'PressName' => $name,
                                    'PressDate' => $date,
                                    'PressHeadline' => $headline,
                                    'PressLink' => $link,
                                ];

                                $this->PressModel->insert($insertData);
                                print_r($name." ".$date." ".$headline." ".$link);
                                echo '<br>';
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
            log_message('error', 'Cron: '.$e->getMessage());
            return false;
        }
    }

    public function cleanCronTable()
    {
        try {
            $this->CronModel->deleteOldRecords();
            $this->updateCronStatus('OK');
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
            log_message('error', 'Cron: '.$e->getMessage());
            return false;
        }
    }

    public function fixPaymentForwardID()
    {
        $this->load->model('Payment_model', 'PaymentModel');
        $payment = $this->PaymentModel->fixPayForward();
        if (empty($payment)) {
            return false;
        }

        foreach ($payment as $item) {
            $paymentForward = $this->initAddressPayment($item);
            $arrUpdate = array(
                'PaySecret' => $paymentForward['PaySecret'],
                'PayForwardID' => $paymentForward['PayForwardID'],
                'PayAddress' => $paymentForward['PayAddress'],
            );
            $this->PaymentModel->update($arrUpdate, array('PayID' => $item['PayID']));
        }
    }

    public function base64ToImage()
    {
        $this->load->model('Project_model', 'ProjectModel');
        $projects = $this->ProjectModel->getConvertBase64();
        if (empty($projects)) {
            return false;
        }

        if (ENVIRONMENT == 'production') {
            $path = '/home/website/apiv2/public/logos/';
        } else {
            $path = '/Volumes/www/logos/';
        }

        foreach ($projects as $item) {
            if ($item['ProjImage'] != '') {
                $fileName = $path . $item['ProjID'] . '.png';
                echo $this->convertBase64ToImage($fileName, $item['ProjImage']) . "\n";
            }

            if ($item['ProjImageLarge'] != '') {
                $fileName = $path . $item['ProjID'] . '_large.png';
                echo $this->convertBase64ToImage($fileName, $item['ProjImageLarge']) . "\n";
            }

            $this->ProjectModel->update(['ProjIsConvertBase64' => 1], ['ProjID' => $item['ProjID']]);
        }
    }

    public function adminBase64ToImage()
    {
        $this->load->model('Project_model', 'ProjectModel');
        $projects = $this->ProjectModel->getConvertBase64();
        if (empty($projects)) {
            return false;
        }

        if (ENVIRONMENT == 'production') {
            $path = '/home/website/apiv2/public/logos/';
        } else {
            $path = '/Volumes/www/logos/';
        }

        foreach ($projects as $item) {
            if ($item['ProjImage'] != '') {
                $fileName = $path . $item['ProjID'] . '.png';
                echo $this->convertBase64ToImage($fileName, $item['ProjImage']) . "\n";
            }

            if ($item['ProjImageLarge'] != '') {
                $fileName = $path . $item['ProjID'] . '_large.png';
                echo $this->convertBase64ToImage($fileName, $item['ProjImageLarge']) . "\n";
            }

            $this->ProjectModel->update(['ProjIsConvertBase64' => 1], ['ProjID' => $item['ProjID']]);
        }

        header("Location: ".base_url()."change/siteadmin.php?mode=4");
        exit();
    }

    public function setTeamGroup()
    {
        $this->load->model('People_project_model', 'PeopleProjectModel');
        $this->PeopleProjectModel->setTeamGroup();
        header("Location: ".base_url()."change/siteadmin.php?mode=3");
        exit();
    }

    /**
     * run once time not in cron job
     */
    public function fixTeamImage()
    {
        $this->load->model('People_model', 'PeopleModel');
        $peoples = $this->PeopleModel->getData();

        foreach ($peoples as $item) {
            if ($item['PeoplePicture'] == '') {
                continue;
            }

            $wrongPath = FCPATH . 'public/uploads/logo/' . $item['PeoplePicture'];
            $correctPath = FCPATH . 'public/uploads/team/' . $item['PeoplePicture'];
            if (file_exists($wrongPath)) {
                if (copy($wrongPath, $correctPath)) {
                    echo "$correctPath \n";
                }

            }
        }
    }

    public function testBlockChain()
    {
        try {
            $this->load->model('Payment_model', 'PaymentModel');
            $this->load->model('Submission_model', 'SubmissionModel');
            $this->load->model('Project_model', 'ProjectModel');

            $payment = $this->PaymentModel->getPaymentCronProcess();
            if (empty($payment)) {
                echo 'Empty List';
            }

            // request blockchain
            $addressList = array();
            foreach ($payment as $item) {
                $addressList[] = $item['PayAddress'];
            }
            $rawMultiAddress    = implode('|', $addressList);
            $blockchainUrl      = 'https://blockchain.info/multiaddr?active='.$rawMultiAddress;
            $blockchainAddress  = grab_url_payment($blockchainUrl);
            $blockchainInfo     = json_decode($blockchainAddress, true);

            echo 'Total Address:' . count($addressList);
            dd($blockchainInfo);
        } catch (\Exception $e) {
            log_message('error', 'Cron: '.$e->getMessage());
        }
    }

    public function setProjectPackage()
    {
        try {
            $this->load->model('Cron_model', 'CronModel');
            $this->CronModel->setProjPackage();
            $this->updateCronStatus('OK');
        } catch (\Exception $e) {
            $this->updateCronStatus($e);
            log_message('error', 'Cron: '.$e->getMessage());
        }
    }

    /**
     * run once time
     */
    public function requestPaymentServer()
    {
        $this->load->model('Cron_model', 'CronModel');
        $arrPayment = $this->CronModel->getSubmissionNotPaidRequestServer();
        if (empty($arrPayment)) {
            return FALSE;
        }

        $client = new Client();
        $endpoint = 'https://pay.coinschedule.com/order_create.php';
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

        foreach ($arrPayment as $payment) {
            $requestBody = [
                'total' => $payment['PayAmount'],
                'payaddress' => $payment['PayAddress'],
                'details' => '[{"desc": "Standard Listing","qty": "1","amt":"'.$payment['PayAmount'].'"}]',
                'hash' => $payment['SubHashCode'],
                'email' => $payment['tx_email'],
                'projectname' => $payment['SubCoinName']
            ];

            $response = $client->request('POST', $endpoint, [
                'headers' => $headers,
                'form_params' => $requestBody
            ]);

            // update PayRequestServer
            $this->load->model('Payment_model', 'PaymentModel');
            $statusCode = $response->getStatusCode();
            $payRequestServer = 3;
            if ($statusCode == 200) {
                $payRequestServer = 2;
            }
            $this->PaymentModel->update(['PayRequestServer' => $payRequestServer], ['PayID' => $payment['PayID']]);
        }
    }

    /**
     * Call when needed
     */
    public function generatePayAddress()
    {
        $this->load->model('Submission_model', 'SubmissionModel');
        $this->load->model('Payment_model', 'PaymentModel');
        $submission = $this->SubmissionModel->getSubmissionById($_GET['SubID']);
        if (empty($submission)) {
            return FALSE;
        }

        $payment = $this->PaymentModel->getPaymentBySubmission($submission['SubID']);
        if (empty($payment)) {
            return FALSE;
        }


        // Provide your Token. Replace the given one with your app Token
        // https://accounts.blockcypher.com/dashboard
        $token = $this->config->config['blockcypher'];
        $destinationAddress = $this->config->config['destination_address'];

        // SDK config
        $config = array(
            'mode' => 'live',
            'log.LogEnabled' => true,
            'log.FileName' => FCPATH . 'application/logs/BlockCypher_NEW.log',
            'log.LogLevel' => 'INFO', // PLEASE USE 'INFO' LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'validation.level' => 'log',
        );

        $apiContext = ApiContext::create(
            'main', 'btc', 'v1',
            new SimpleTokenCredential($token),
            $config
        );


        $secret = $payment['PaySecret'];
        $my_callback_url = base_url().'payment?h='.$submission['SubHashCode'].'&secret='.$secret;
        $paymentForwardClient = new PaymentForwardClient($apiContext);
        $options = array(
            'callback_url' => $my_callback_url
        );
        $paymentForward = $paymentForwardClient->createForwardingAddress($destinationAddress, $options);
        $paymentForward = $paymentForward->toArray();
        $address = $paymentForward['input_address'];
        $PayForwardID = $paymentForward['id'];

        if (!empty($address)) {
            $arrUpdate = array(
                'PayAddress' => $address,
                'PayForwardID' => $PayForwardID,
                'PayCreatedDate' => date('Y-m-d H:i:s')
            );
            $this->PaymentModel->update($arrUpdate, ['PayID' => $payment['PayID']]);
        }

        echo $address;
    }

    /**
     * Call once time
     */
    public function moveTeamIDFile()
    {
        $this->load->model('Submission_team_model', 'SubmissionTeamModel');
        $this->load->model('People_model', 'PeopleModel');
        $listTeam = $this->SubmissionTeamModel->getTeamPassport();
        $listTeamPeople = $this->PeopleModel->getTeamPassport();

        if (!empty($listTeam)) {
            foreach ($listTeam as $item) {
                $oldName = FCPATH . self::PATH_IMAGE_TEAM . $item['SubTeamPassport'];
                if (file_exists($oldName)) {
                    $newName = FCPATH . self::PATH_IMAGE_PASSPORT . $item['SubTeamPassport'];
                    rename($oldName, $newName);
                }
            }
        }

        if (!empty($listTeamPeople)) {
            foreach ($listTeamPeople as $item) {
                $oldName = FCPATH . self::PATH_IMAGE_TEAM . $item['PeoplePassport'];
                if (file_exists($oldName)) {
                    $newName = FCPATH . self::PATH_IMAGE_PASSPORT . $item['PeoplePassport'];
                    rename($oldName, $newName);
                }
            }
        }
    }

    private function convertBase64ToImage($path, $base64)
    {
        $ifp = fopen( $path, 'wb' );
        fwrite($ifp, base64_decode($base64 ));
        fclose($ifp);

        return $path;
    }

    private function initAddressPayment($submission)
    {
        // Provide your Token. Replace the given one with your app Token
        // https://accounts.blockcypher.com/dashboard
        $token = $this->layoutData['config']['blockcypher'];
        $destinationAddress = $this->layoutData['config']['destination_address'];

        // SDK config
        $config = array(
            'mode' => 'live',
            'log.LogEnabled' => true,
            'log.FileName' => FCPATH . 'application/logs/BlockCypher_NEW.log',
            'log.LogLevel' => 'INFO', // PLEASE USE 'INFO' LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'validation.level' => 'log',
        );

        $apiContext = ApiContext::create(
            'main', 'btc', 'v1',
            new SimpleTokenCredential($token),
            $config
        );

        $secret = md5(uniqid(rand(), TRUE).time());
        $my_callback_url = base_url().'payment?h='.$submission['SubHashCode'].'&secret='.$secret;
        $paymentForwardClient = new PaymentForwardClient($apiContext);
        $options = array(
            'callback_url' => $my_callback_url
        );
        $paymentForward = $paymentForwardClient->createForwardingAddress($destinationAddress, $options);
        $paymentForward = $paymentForward->toArray();
        $address = $paymentForward['input_address'];
        $PayForwardID = $paymentForward['id'];

        return array(
            'PaySecret' => $secret,
            'PayAddress' => $address,
            'PayForwardID' => $PayForwardID,
        );
    }

    private function processPayment($sub)
    {
        try {
            $this->PaymentModel->updatePayStatus($sub);
            $submissionProject = $this->SubmissionModel->getSubmissionProject($sub['SubID']);
            if (!empty($submissionProject)) {
                $ProjID = $this->ProjectModel->submissionToProject($sub);
                $this->processProjectImage($sub, $ProjID);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Cron: '.$e->getMessage());
            return false;
        }
    }

    private function processProjectImage($sub, $ProjID)
    {

        try {
            $config['image_library'] = 'gd2';
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = FALSE;

            if ($sub['SubLogo']) {
                $filename = FCPATH . self::PATH_IMAGE_LOGO . $sub['SubLogo'];
                $grabImage = true;
            } else {
                $filename = FCPATH . self::PATH_IMAGE_LOGO . $sub['SubID'].rand(100, 999).time().'.jpg';
                $grabImage = grab_image($sub['SubLogoLink'], $filename);
            }

            if ($grabImage && file_exists($filename)) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileinfo = finfo_file($finfo, $filename);

                if (strpos($fileinfo, 'image') !== FALSE) {

                    $ProjImage = '';
                    $ProjImageLarge = '';

                    // 48x48
                    $this->load->library('image_lib');
                    $config['source_image'] = $filename;
                    $config['width']        = 48;
                    $config['height']       = 48;
                    $config['new_image']    = pathinfo($filename, PATHINFO_DIRNAME).'/'.pathinfo($filename, PATHINFO_FILENAME).'48x48.'.pathinfo($filename, PATHINFO_EXTENSION);
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    if (file_exists($config['new_image']) && file_get_contents($config['new_image'])) {
                        $ProjImageLarge = base64_encode(file_get_contents($config['new_image']));
                    }

                    // 16x16
                    $config['source_image'] = $filename;
                    $config['width']        = 16;
                    $config['height']       = 16;
                    $config['new_image']    = pathinfo($filename, PATHINFO_DIRNAME).'/'.pathinfo($filename, PATHINFO_FILENAME).'16x16.'.pathinfo($filename, PATHINFO_EXTENSION);
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    if (file_exists($config['new_image']) && file_get_contents($config['new_image'])) {
                        $ProjImage = base64_encode(file_get_contents($config['new_image']));
                    }

                    // update project images
                    $arrUpdate = array(
                        'ProjImage' => $ProjImage,
                        'ProjImageLarge' => $ProjImageLarge,
                    );
                    $this->ProjectModel->update($arrUpdate, array('ProjID' => $ProjID));
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Cron: '.$e->getMessage());
        }

    }

    private function processUpgradePayment($address)
    {
        /**
         * Gold = set ProjSponsored flag to 1 (in tbl_projects) and also set ProjPackage to 2
         * Silver = leave ProjSponsored at 0 and set ProjPackage to 1
         * Platinum = set ProjSponsored to 1, ProjPackage to 2 and ProjPlatinum to 1
         */

        $this->load->model('Project_model', 'ProjectModel');
        $this->load->model('Project_log_model', 'ProjectLogModel');
        $payment = $this->UpgradePaymentModel->getPaymentByAddress($address);

        if (!empty($payment)) {

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

            // update db
            $this->ProjectLogModel->updateProjectLog('cron', $arrProjectField, $payment['ProjID']);
            $this->ProjectModel->update($arrProjectField, array('ProjID' => $payment['ProjID']));
            $this->UpgradePaymentModel->update(
                array('PayStatus' => '1', 'PayDatetime' => date('Y-m-d H:i:s')),
                array('PayAddress' => $address)
            );

            return true;
        }

        return false;
    }

    private function updateCronStatus($response)
    {
        if (is_object($response) && $response instanceof \Exception) {
            $this->CronModel->update(
                array('status' => 0, 'response' => $response->getMessage()),
                array('id' => $this->cronId)
            );
        } else {
            $this->CronModel->update(
                array('status' => 1, 'response' => $response),
                array('id' => $this->cronId)
            );
        }
    }
}
