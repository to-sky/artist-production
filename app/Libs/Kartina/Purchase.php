<?php

namespace App\Libs\Kartina;


use App\Models\Order;
use App\Models\User;

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
        'confirmOrder' => '/ConfirmOrderCommand.cmd',
        'registerClient' => '/ClientRegisterCommand.cmd',
        'loginClient' => '/ClientLoginCommand.cmd',
        'reserveCart' => '/ReserveCurrentOrderCommand.cmd',
        'confirmPayment' => '/ChangeOrderStatusCommand.cmd',
        'setPaymentType' => '/SavePaymentOptions.cmd',
        'setDeliveryType' => '/SaveDeliveryOptions.cmd',
    ];

    /**
     * Api constructor.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct()
    {
        parent::__construct();
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
        if (empty($this->auth)) $this->auth = $this->getAuth();

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

    public function setPaymentType($orderId)
    {
        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            [
                'paymentType' => 'BANK_PAY',
                'order' => $orderId,
                'format' => 'json',
            ]
        );

        return $resp;
    }

    public function setDeliveryType($orderId)
    {
        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            [
                'deliveryType' => 'ETICKET',
                'order' => $orderId,
                'format' => 'json',
            ]
        );

        return $resp;
    }

    public function confirmOrder($orderId, $status = 0)
    {
        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            [
                'login' => config('kartina.master_login'),
                'password' => config('kartina.master_password'),
                'order' => $orderId,
                'status' => $status,
            ],
            [
                'method' => 'POST',
            ]
        );

        return $resp;
    }

    public function registerClient($data)
    {
        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            [
                'authProvider' => 'email',
                'login' => $data['email'],
                'password' => config('kartina.default_client_password'),
                'firstName' => $data['first_name'],
                'lastName' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ],
            [
                'method' => 'POST',
            ]
        );

        return $resp;
    }

    public function loginClient($email)
    {
        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            [
                'authProvider' => 'email',
                'login' => $email,
                'password' => config('kartina.default_client_password'),
            ],
            [
                'method' => 'POST',
            ]
        );

        return $resp;
    }

    public function reserveCart()
    {
        $resp = $this->sendAuthRequest(
            $this->host.$this->urls[__FUNCTION__],
            [
                'useBonusAccount' => 0,
                'inviteTicket' => 0,
                'paymentType' => config('kartina.default_payment_type'),
                'deliveryType' => config('kartina.default_delivery_type'),
            ],
            [
                'method' => 'POST',
            ]
        );

        return $resp;
    }
};