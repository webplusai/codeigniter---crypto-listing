<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\BadResponseException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MY_Rest_Controller extends CI_Controller
{

    const TIMEOUT = 10;
    private $authenticationToken = '';
    protected $oException;



    public function __construct()
    {
        parent::__construct();
        $this->load->helper('common');
        $this->setAuthenticationToken();
    }

    public function handleException(Exception $oException)
    {
        log_message('error', $oException->getMessage()
            . ' ' . $oException->getCode()
            . ' ' . $oException->getFile()
            . ' ' . $oException->getLine()
            . "\n" . $oException->getTraceAsString());

        $this->renderError('E1000', 'Bad Request');
    }

    public function validateRequest($method, $needAuthentication = false)
    {
        if ($this->input->method(TRUE) != strtoupper($method)) {
            $this->renderError('R1000', 'Invalid Request', 400);
        }

        if ($needAuthentication && !$this->validAuthentication()) {
            $this->renderError('A1000', 'Unauthorized', 401);
        }
    }

    /**
     * @param $method
     * @param $url
     * @param $requestBody
     * @param $headers
     * @return array
     */
    public function doRequest($method, $url, $requestBody, $headers)
    {
        try {
            $client = new Client();
            $request = new Request($method, $url, $headers, $requestBody);
            $response = $client->send($request, array(
                'timeout' => self::TIMEOUT
            ));

            return $this->parsingResponse($response);
        } catch (BadResponseException $e) {
            return $this->parsingResponse($e->getResponse());
        }
    }

    public function getAuthenticationToken()
    {
        return $this->authenticationToken;
    }

    public function validAuthentication()
    {
        $privateKey = $this->config->item('api_payment_key');
        if ($this->getAuthenticationToken() == $privateKey) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param $errorCode
     * @param $errorMessage
     * @param int $status
     * @param null | array $errorList
     */
    public function renderError($errorCode, $errorMessage, $status = 400, $errorList = NULL)
    {
        $data = [
            'code' => $errorCode,
            'message' => $errorMessage
        ];
        if (!empty($errorList)) {
            $data['error'] = $errorList;
        }

        $this->renderJson($data, $status);
    }

    /**
     * @param $data
     * @param int $status
     */
    public function renderJson($data, $status = 200)
    {
        $this->output->set_status_header($status);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit();
    }

    public function logRequestEndpoint()
    {
        // create a log channel
        $logPath = FCPATH . 'application/logs/endpoint/' . date('Ymd') . '.log';
        $log = new Logger('request');
        $log->pushHandler(new StreamHandler($logPath, Logger::INFO));

        $arrLogDump = [];
        $arrLogDump['requestData'] = $this->input->post(NULL, FALSE);
        $arrLogDump['requestHeader'] = $this->input->request_headers();
        $log->info(json_encode($arrLogDump));
    }

    private function parsingResponse(ResponseInterface $response)
    {
        $responseData = [];
        $responseData['statusCode'] = $response->getStatusCode();
        $responseData['contentType'] = $response->getHeader('Content-Type');
        $bodyStringData = $response->getBody()->getContents();

        if (stripos($responseData['contentType'][0], 'json') !== false) {
            $responseData['data'] = json_decode($bodyStringData, true);
        } else {
            $responseData['data'] = $bodyStringData;
        }

        return $responseData;
    }

    private function setAuthenticationToken()
    {
        $this->authenticationToken = $this->input->get_request_header('Authentication');
    }
}