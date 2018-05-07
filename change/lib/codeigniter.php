<?php
require_once(ROOT_PATH . 'vendor/autoload.php');
require_once(ROOT_PATH . 'change/lib/cake.php');
require_once(ROOT_PATH . 'change/vendor/predis/src/Autoloader.php');

if (!function_exists('flushAllCache')) {
    function flushAllCache()
    {
        // flushall redis
        Predis\Autoloader::register();
        $options = [
            'parameters' => [
                'password' => REDIS_PASSWORD
            ]
        ];
        $client = new Predis\Client([
            'scheme' => REDIS_SOCKET,
            'host'   => REDIS_HOST,
            'port'   => REDIS_PORT,
        ], $options);

        $client->flushall();
    }

    function calcIcoRank($projectId)
    {
        $url = BASE_URL . 'ajax/calcIcoRank';
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
        $requestBody = [
            'projID' => $projectId
        ];
        return doRequest('POST', $url, $requestBody, $headers);
    }

    function doRequest($method, $url, $requestBody, $headers)
    {
        $client = new GuzzleHttp\Client();
        $response = $client->request($method, $url, [
            'headers' => $headers,
            'form_params' => $requestBody
        ]);
        return parsingResponse($response);
    }

    function parsingResponse($response)
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
}
