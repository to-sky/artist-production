<?php

namespace App\Libs\Kartina;


class Purchase extends Base
{
    /**
     * Auth token
     *
     * @var void
     */
    protected $auth;

    protected $host = 'https://kassir.kartina.tv';

    /**
     * API urls
     *
     * @var array
     */
    protected $urls = [
        'getAuth' => '/LoginCommand.cmd',
        'getHallUpdate' => '/UpdateFlashEventDataCommand.cmd',
        'addToOrder' => '/ChangePlaceCommand.cmd',
        'removeFromOrder' => '/DeletePlaceFromCartCommand.cmd',
        'getOrder' => '/GetOrderCommand.cmd',
    ];

    /**
     * Api constructor.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth = $this->getAuth();
    }

    /**
     * Get auth token
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAuth()
    {
        if (session()->has('kartina_purchase_auth')) {
            $auth = session()->get('kartina_purchase_auth');
        } else {
            $auth = $this->sendRequest(
                $this->host.$this->urls[__FUNCTION__],
                ['__uid' => config('kartina.kartina_uid')]
            );
            session()->put('kartina_purchase_auth', $auth);
        }

        if (! isset($auth['__auth'])) {
            return null;
        }

        return $auth['__auth'];
    }

    public function clearSessionUID()
    {
        session()->remove('kartina_purchase_auth');
    }

    /**
     * Send request with auth params
     *
     * @param $url
     * @param array $params
     * @param array $options
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendAuthRequest($url, $params = [], $options = [])
    {
        try {
            return $this->sendRequest($url, ['__auth' => $this->auth] + $params, $options + ['throw_errors' => true]);
        } catch (\Exception $e) {
            if ($e->getCode() == 550) {
                $this->clearSessionUID();

                return $this->sendRequest($url, ['__auth' => $this->auth] + $params, $options + ['throw_errors' => true]);
            }

            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Checks if request not empty and not errors
     *
     * @param $request
     * @param bool $full
     * @param string $key
     * @return \Illuminate\Support\Collection|null
     */
    public function filterRequest($request, $full = false, $key = 'list')
    {
        if ($full) {
            return collect($request);
        }

        if (! isset($request[$key])) {
            return null;
        }

        return collect($request[$key]);
    }

    protected function parseHallUpdateData($text)
    {
        $document = new \DOMDocument();
        @$document->loadHTML($text);

        $ts = 0;
        /** @var \DOMElement $update */
        foreach ($document->getElementsByTagName('update') as $update) {
            $ts = $update->attributes->getNamedItem('ts')->value ?? 0;
        }

        $places = [];
        /** @var \DOMElement $node */
        foreach ($document->getElementsByTagName('p') as $node) {
            $id = $node->attributes->getNamedItem('id')->value;
            $free = (int)$node->attributes->getNamedItem('free')->value;
            $status = $node->attributes->getNamedItem('status')->value;

            $places[$id] = [
                'free' => $free,
                'reserved' => (!$free && ($status == 2 || $status == 4)) ? 1 : 0,
            ];
        }

        return compact('places', 'ts');
    }

    public function getHallUpdate($eventId, $timestamp = null)
    {
        $params = ['event' => $eventId];
        if ($timestamp) $params['ts'] = $timestamp;

        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            $params,
            [
                'method' => 'POST',
                'json' => false,
            ]
        );

        $dataArray = $this->parseHallUpdateData($resp);

        return $dataArray;
    }

    public function getOrder($orderId = null)
    {
        $params = [];
        if ($orderId) $params['order'] = $orderId;

        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            $params
        );

        return $resp;
    }

    public function addToOrder($eventId, $placeId, $count = 1)
    {
        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            [
                'event' => $eventId,
                'pid' => $placeId,
                'ticketsCount' => $count,
            ],
            [
                'method' => 'POST',
            ]
        );

        return $resp;
    }

    public function removeFromOrder($ticketId)
    {
        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            [
                'orderItem' => $ticketId,
            ],
            [
                'method' => 'POST',
            ]
        );

        return $resp;
    }
}