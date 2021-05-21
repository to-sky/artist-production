<?php

namespace App\Libs\Kartina\Traits;


use App\Libs\Kartina\Purchase;
use App\Models\User;

trait ClientTrait
{
    /**
     * @var Purchase|null
     */
    protected $_api;

    protected function initClientTrait()
    {
        $this->_api = new Purchase();
    }

    public function syncKartinaClientForUser(User $user)
    {
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
}