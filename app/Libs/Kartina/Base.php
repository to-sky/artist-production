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
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest($url, array $params)
    {
        try {
            $response = $this->client->request('GET', $url, ['query' => $params]);
        } catch (RequestException $e) {
            return 'Error: ' . $e->getMessage();
        }

        $responseContent = $response->getBody()->getContents();

        return json_decode($responseContent, true);
    }
}
