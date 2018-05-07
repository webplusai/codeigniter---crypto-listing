<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/core/MY_Rest_Controller.php';
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;

class Rest extends MY_Rest_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->logRequestEndpoint();
    }

    public function updatePaymentAmount()
    {
        try {
            $this->validateRequest('POST', TRUE);
            $payAmount = $this->input->post('payAmount');
            $subHash = $this->input->post('SubHash');
            if (!is_numeric($payAmount)) {
                $this->renderError('V1000', 'Validation of payAmount failed', 200);
            }

            $this->load->model('Payment_model', 'PaymentModel');
            $result = $this->PaymentModel->updateAmountByHash($subHash, $payAmount);
            if (!$result) {
                $this->renderError('V1001', 'Validation of SubHash failed', 200);
            }

            $data = ['message' => 'OK'];
            $this->renderJson($data);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function createOrder()
    {
        try {
            $this->validateRequest('POST', TRUE);
            $subHash = $this->input->post('SubHash');

            $this->load->model('Submission_model', 'SubmissionModel');
            $payment = $this->SubmissionModel->getSubmissionPaymentByHash($subHash);
            if (empty($payment)) {
                $this->renderError('V1001', 'SubHash is not exit', 200);
            }


            // Request Order Create
            $client = new Client();
            $endpoint = 'https://pay.coinschedule.com/order_create.php';
            $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

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

            $data = ['message' => 'OK'];
            $this->renderJson($data);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

}