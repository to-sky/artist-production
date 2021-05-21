<?php
namespace App\Libs\Kartina\Traits;

use App\Libs\Kartina\Purchase;
use App\Models\Order;
use App\Models\User;

trait OrderTrait
{
    /**
     * @var Purchase|null
     */
    protected $_api;

    protected function initOrderTrait()
    {
        $this->_api = new Purchase();
    }

    /**
     * Checks if current order should have user on kartina.tv
     *
     * @param Order $order
     *
     * @return bool
     */
    protected function shouldHaveKartinaId(Order $order)
    {
        foreach ($order->tickets as $ticket) {
            if ($ticket->kartina_id) return true;
        }

        return false;
    }

    public function getKartinaClientForUser(User $user)
    {
        if (!$user->kartina_id) {
            $client = $this->_api->getClients($user->email);

            if (empty($client)) {
                $resp = $this->_api->registerClient([
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'phone' => $user->profile->phone ?? null,
                ]);

                $clientId = $resp['clientId'];
            } else {
                $clientId = $client['id'];
            }

            $user->kartina_id = $clientId;
            $user->save();
        }

        return $this->_api->setClient($user->kartina_id);
    }

    protected function confirmKartinaOrder(Order $order, User $user = null)
    {
        if (empty($user)) $user = $order->user;

        $this->getKartinaClientForUser($user);

        $kartinaOrder = $this->_api->getOrder();

        $order->kartina_id = $kartinaOrder['orderId'];
        $order->save();

        $this->_api->setDeliveryType($order->kartina_id);
        $this->_api->setPaymentType($order->kartina_id);
        $this->_api->confirmOrder($order->kartina_id);
    }

    protected function confirmPaymentKartinaOrder(Order $order)
    {
        $this->_api->confirmOrder($order->kartina_id, 1);
    }

    protected function reserveKartinaOrder(Order $order, User $user = null)
    {
        if (empty($user)) $user = $order->user;

        $this->getKartinaClientForUser($user);

        $kartinaOrder = $this->_api->getOrder();

        $order->kartina_id = $kartinaOrder['orderId'];
        $order->save();

        $this->_api->reserveCart();
    }

    protected function saleKartinaOrder(Order $order, User $user = null)
    {
        if (empty($user)) $user = $order->user;
        if ($user) $this->getKartinaClientForUser($user);

        $kartinaOrder = $this->_api->getOrder();

        $order->kartina_id = $kartinaOrder['orderId'];
        $order->save();

        $this->_api->saleCart();

    }

    protected function revertKartinaOrder(Order $order)
    {
        $this->_api->revertOrder($order->kartina_id);
    }
}