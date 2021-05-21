<?php

namespace App\Libs\Kartina;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

abstract class Base
{
    protected  $client;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Send request
     *
     * @param $url
     * @param array $params
     * @param array $options
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest($url, array $params, $options = [])
    {
        $method = $options['method'] ?? 'GET';

        $requestOptions = [];
        if ($method == "POST") {
            $requestOptions['form_params'] = $params;
        } else {
            $requestOptions['query'] = $params;
        }

        try {
            $response = $this->client->request($method, $url, $requestOptions);
        } catch (RequestException $e) {
            if (isset($options['throw_errors']) && $options['throw_errors']) {
                throw $e;
            } else {
                return 'Error: ' . $e->getMessage();
            }
        }

        $responseContent = $response->getBody()->getContents();

        if (isset($options['json']) && $options['json'] === false) return $responseContent;

        return json_decode($responseContent, true);
    }


}
