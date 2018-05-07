<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/core/MY_Controller.php';


class Requestapi extends MY_Controller
{

    public $isMaintenance = FALSE;

    public function __construct()
    {
        parent::__construct();
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->load->model('Requestapi_model', 'RequestapiModel');
        $this->load->model('User_model', 'UserModel');


        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        if ($this->isMaintenance) {
            $this->layoutData['head']['title'] = 'Site Maintenance';
            $this->renderView('maintenance.twig');
            exit();
        }
    }

    public function index()
    {
        try {
            $this->layoutData['apirequests'] = "<pre>".print_r($this->RequestapiModel->getAllRequests(), true)."</pre>";
        }
        catch (Exception $exception) {
            echo $exception->getMessage();
            exit();
        }
        //$this->layoutData['apirequests'] = "<p>".time()."</p>";
        $this->layoutData['head']['title'] = 'Request API Key form';
        $this->renderView('requestapi.twig');

    }
}
