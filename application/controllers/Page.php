<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/core/MY_Controller.php';

class Page extends MY_Controller
{

    public function __construct($isAjax = false)
    {
        parent::__construct();
    }

    public function icos()
    {
        $this->load->model('Data_model', 'DataModel');
        $icoResult = $this->DataModel->getICOResult();
        $htmlResult = '';
        if (!empty($icoResult)) {
            foreach ($icoResult as $ico) {
                $htmlResult .= '<tr><td>'.$ico['ICOName'].'</td><td>'.$ico['ProjCatName'].'</td><td>'.$ico['EndDate'].'</td><td>$'.number_format($ico['TotalUSD'],0,'.',',').'</td></tr>';
            }
        }

        $dataTableOrder = 3;
        if ($this->agent->is_mobile()) {
            $dataTableOrder = 1;
        }

        $this->layoutData['dataTableOrder'] = $dataTableOrder;
        $this->layoutData['htmlResult'] = $htmlResult;
        $this->layoutData['head']['title'] = 'ICO Results - List of ICOs';
        $this->layoutData['head']['keyword'] = 'ico,icos,ico list,cryptocurrency,crowdsale,token sale,crowdfunding,cryptocoin,bitcoin,altcoin,roadmap';
        $this->layoutData['head']['description'] = truncate("This page shows the results of Cryptocurrency ICOs and is updated regularly. Note that the total raised information is provided by the icos themselves and not independently verified by Coinschedule.");
        $this->renderView('icos.twig');
    }

    public function stats()
    {
        $this->layoutData['isDefer'] = false;
        $this->load->model('Data_model', 'DataModel');
        $year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');
        $statsResult = $this->DataModel->getStatsResult($year);

        $monthFig = array_fill(1, 12, "''");
        $yearTotal = 0;
        foreach ($statsResult['months'] as $month) {
            $monthFig[$month['Month']] = $month['Total'];
            $yearTotal += $month['Total'];
        }
        $this->layoutData['yeartotal'] = number_format($yearTotal, 0, '.', ',');

        $drawChartArray = '';
        if (!empty($statsResult['icoCats'])) {
            foreach ($statsResult['icoCats'] as $icocat) {
                $drawChartArray .= "\n['".$icocat['ProjCatName']."',".$icocat['Total']."],";
            }
        }
        $this->layoutData['drawChartArray'] = substr($drawChartArray, 0, -1);;

        $this->layoutData['legend'] = '';
        if ($this->agent->is_mobile()) {
            $this->layoutData['legend'] = 'position: \'top\',maxLines:10,';
        }

        $currentYear = date('Y');
        $this->layoutData['selectYear'] = '';
        for ($y = $currentYear; $y >= 2016; $y --) {
            if ($y == $year) {
                $this->layoutData['selectYear'] .= '<option selected value="'.$y.'">'.$y.'</option>';
            } else {
                $this->layoutData['selectYear'] .= '<option value="'.$y.'">'.$y.'</option>';
            }
        }

        $this->layoutData['monthFig'] = '';
        foreach ($monthFig as $fig) {
            $this->layoutData['monthFig'] .= $fig . ',';
        }
        $this->layoutData['monthFig'] = substr($this->layoutData['monthFig'], 0, -1);

        $this->layoutData['topTen'] = '';
        if (!empty($statsResult['topTen'])) {
            $pos = 1;
            foreach ($statsResult['topTen'] as $top) {
                $this->layoutData['topTen'] .= '<tr><td>'.$pos.'</td><td></td><td>'.$top['ICOName'].'</td><td align="right">$'.number_format($top['Total'],0,'.',',').'</td></tr>';
                $pos++;
            }
        }

        $this->layoutData['StatsLastUpdatedOn'] = $this->getSetting(15);

        $scriptJS = [];
        $scriptJS['common'] = base_url() . 'public/dist/common-min.js';
        $scriptJS['new_common'] = base_url() . 'public/dist/new-common-min.js';
        $scriptJS['jqplot'] = base_url() . 'public/libs/jqplot/excanvas.js';
        $scriptJS['stats'] = base_url() . 'public/js/stats.js';
        $this->renderJS($scriptJS, false);

        $this->layoutData['reviveBanner'] = true;
        $this->layoutData['numOfICO'] = $statsResult['numOfICO'];
        $this->layoutData['year'] = $year;
        $this->layoutData['head']['title'] = 'Cryptocurrency ICO Statistics';
        $this->layoutData['head']['description'] = 'Statistics and graphs about the ICO market, past token sales, biggest token sales and total funds raised';
        $this->renderView('stats.twig');
    }

    public function advertise()
    {
        $initFormData = [
            'tx_name' => '',
            'tx_email' => '',
            'tx_info' => '',
        ];
        $this->layoutData['formData'] = $initFormData;
        $this->layoutData['isValid'] = true;
        $this->layoutData['formError'] = false;
        $this->layoutData['isSuccess'] = false;

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="diverror">', '</p>');

        if ($this->input->post('submit', TRUE)) {

            $this->layoutData['isValid'] = false;
            $formData = $this->input->post(NULL, TRUE);
            $this->layoutData['formData'] = $formData;

            $this->form_validation->set_rules('tx_name', 'Your Name', 'required');
            $this->form_validation->set_rules('tx_email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|valid_recaptcha');
            if ($this->form_validation->run() === TRUE) {
                $this->layoutData['isValid'] = true;
                $this->layoutData['isSuccess'] = true;
                $this->layoutData['formData'] = $initFormData;

                // insert enquiries
                $this->load->model('Enquiry_model', 'EnquiryModel');
                $arrInsert = [
                    'EnqName' => $formData['tx_name'],
                    'EnqEmail' => $formData['tx_email'],
                    'EnqInfo' => $formData['tx_info'],
                ];
                $this->EnquiryModel->insert($arrInsert);

                // send mail
                $formData['listingFee'] = $this->getSetting(2);
                $this->load->helper('email');
                $mailBody = $this->load->view('emails/advertise', $formData, true);
                $sendFrom = array('email' => $this->config->config['send_from'], 'name' => $this->config->config['send_from_name']);
                $sendTo1 = array('email' => $formData['tx_email'], 'name' => 'moon');
                $subject = "Coinschedule Marketing Enquiry";
                send_email($sendFrom, $sendTo1, $subject, $mailBody);
            } else {
                $this->layoutData['formError'] = validation_errors();
            }
        }

        $this->layoutData['head']['title'] = 'The best cryptocurrency ICOs (Initial Coin Offering) Crowdsale and Token Sales List';
        $this->renderView('advertise.twig');
    }

    public function about()
    {
        $this->renderPageInfo($this->mappingPage['About']);
        $this->renderView('about.twig');
    }

    public function press()
    {
        $this->load->model('Press_model', 'PressModel');
        $htmlPress = '';
        $arrPress = $this->PressModel->getPressMention();
        if (!empty($arrPress)) {
            foreach ($arrPress as $press) {
                $htmlPress .=
                    '<a href="'.$press['PressLink'].'" rel="nofollow" target="_blank">
                        <img class="inline" style="padding-right: 10px;" src="'.base_url().'public/uploads/press/'.$press['PressImage'].'">
                        <div class="inline">
                            <h3 style="margin-bottom:2px;">'.$press['PressHeadline'].'</h3>
                            <i>'.date("F jS, Y",strtotime($press['PressDate'])).'</i>
                        </div>
                    </a><hr>';
            }
        }

        $this->layoutData['htmlPress'] = $htmlPress;
        $this->layoutData['head']['title'] = 'Press Mentions';
        $this->renderView('press.twig');
    }

    public function terms()
    {
        $this->renderPageInfo($this->mappingPage['Terms']);
        $this->renderView('terms.twig');
    }

    public function disclaimer()
    {
        $this->renderPageInfo($this->mappingPage['Disclaimer']);
        $this->renderView('disclaimer.twig');
    }

    public function privacypolicy()
    {
        $this->renderPageInfo($this->mappingPage['PrivacyPolicy']);
        $this->renderView('privacypolicy.twig');
    }

    public function cookiespolicy()
    {
        $this->renderPageInfo($this->mappingPage['CookiePolicy']);
        $this->renderView('cookies_policy.twig');
    }

    public function slack()
    {
        redirect('https://join.slack.com/t/coinschedule/shared_invite/MjM2NjY2NDk4NzA0LTE1MDQ3NDEwMzYtZjFiYTVmOGUyZA');
        exit();
    }

    public function link()
    {
        if (isset($_GET['l']) && $_GET['l']) {
            $this->load->model('Link_model', 'LinkModel');
            $link = $this->LinkModel->getLinkById($_GET['l']);
            if (!empty($link)) {
                redirect($link['Link']);
                exit();
            }
        } else if (isset($_GET['fl']) && $_GET['fl']) {
            redirect(urldecode($_GET['fl']));
            exit();
        } else {
            redirect('');
            exit();
        }
    }

    public function packages()
    {
        $this->load->helper('file');
        $btcusdprice = $this->getSetting(8);

        // Make sure prices don't go outside USD price ranges
        $USDpricetol_percent = 20;

        // Banner
        $banprice = 0.853125;
        $banUSDpprice = 2500;
        $banUSDminprice = $banUSDpprice * ((100-$USDpricetol_percent)/100);
        $banUSDmaxprice = $banUSDpprice * ((100+$USDpricetol_percent)/100);
        if (($banprice * $btcusdprice) < $banUSDminprice) { $banprice = round($banUSDminprice/$btcusdprice,4); }
        elseif (($banprice * $btcusdprice) > $banUSDmaxprice) { $banprice = round($banUSDmaxprice/$btcusdprice,4); }

        // Blog
        $blogprice = 0.9375;
        $blogUSDprice = 3000;
        $blogUSDminprice = $blogUSDprice * ((100-$USDpricetol_percent)/100);
        $blogUSDmaxprice = $blogUSDprice * ((100+$USDpricetol_percent)/100);
        if (($blogprice * $btcusdprice) < $blogUSDminprice) { $blogprice = round($blogUSDminprice/$btcusdprice,4); }
        elseif (($blogprice * $btcusdprice) > $blogUSDmaxprice) { $blogprice = round($blogUSDmaxprice/$btcusdprice,4); }

        $this->layoutData['banprice'] = $banprice;
        $this->layoutData['blogprice'] = $blogprice;

        $this->layoutData['Plus_Listing'] = $this->getSetting(9);
        $this->layoutData['Silver_Listing'] = $this->getSetting(10);
        $this->layoutData['Gold_Listing'] = $this->getSetting(11);
        $this->layoutData['Top_Upcoming_Listing'] = $this->getSetting(12);
        $this->layoutData['Platinum_Listing'] = $this->getSetting(13);
        $this->layoutData['Tweet_Post_Telegram'] = $this->getSetting(14);
        $this->layoutData['Listing_Fee'] = $this->getSetting(2);

        $this->layoutData['head']['title'] = 'Marketing Packages';
        $this->renderView('packages.twig');
    }

    public function show404()
    {
        if (strpos($_SERVER['REQUEST_URI'], '.php') !== FALSE) {
            $redirect = str_replace('.php', '.html', $_SERVER['REQUEST_URI']);
            header("Location: ".$redirect."", TRUE, 301);
            exit();
        }

        $this->layoutData['head']['title'] = '404';
        $this->renderView('404.twig');
    }

    public function partners()
    {
        $this->load->model('Partner_model', 'PartnerModel');
        $htmlRender = '';
        $arrData = $this->PartnerModel->getAll();
        if (!empty($arrData)) {
            foreach ($arrData as $item) {
                $htmlRender .=
                    '<div class="partner-item"><a data-id="'.$item['PartID'].'" class="clickPartner" href="'.$item['PartLink'].'" rel="nofollow" target="_blank">
                        <img class="inline" style="padding-right: 10px; height: 100px;" src="'.base_url().'public/uploads/partners/'.$item['PartImage'].'">
                        <div class="inline partner-text">
                            <h3>'.$item['PartName'].'</h3>
                            <p class="partner-p">'.$item['PartServices'].'</p>
                            <i>'.$item['PartDescription'].'</i>
                        </div>
                    </a></div><hr>';
            }
        }

        $this->layoutData['htmlRender'] = $htmlRender;
        $this->layoutData['head']['title'] = 'Partners';
        $this->renderView('partners.twig');
    }

    public function api()
    {
        $this->layoutData['head']['title'] = 'Coinshedule API Documentation';
        $this->layoutData['head']['description'] = 'Coinschedule API Docs';
        $this->renderViewCurlyBraces('api.twig');
    }

    public function requestapitest()
    {
        $this->layoutData['head']['title'] = 'Coinshedule Request API key';
        $this->layoutData['head']['description'] = 'Coinshedule Request API key';
        $this->renderViewCurlyBraces('requestapi.twig');
    }

    public function statsMonthly()
    {
        $this->layoutData['head']['title'] = ' ICO Monthly Figures';
        $this->layoutData['head']['description'] = ' ICO Monthly Figures';
        $this->renderView('monthly.twig');
    }
}
