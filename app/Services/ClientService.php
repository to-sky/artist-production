<?php

namespace App\Services;


use App\Mail\DynamicMails\RegistrationMail;
use App\Models\Address;
use App\Models\Client;
use App\Models\Profile;
use App\Models\Role;
use App\Modules\Admin\Requests\CreateClientRequest;
use App\Modules\Admin\Requests\UpdateClientRequest;
use Illuminate\Database\Eloquent\Builder;
use Hash;

class ClientService
{
    /** @var MailService */
    protected $_mailService;

    /**
     * Underling model class full name
     *
     * @return string
     */
    public function getModelClass()
    {
        return Client::class;
    }

    public function __construct(MailService $mailService)
    {
        $this->_mailService = $mailService;
    }

    /**
     * Get base Query builder for client
     *
     * @return Builder
     */
    protected function _baseQuery()
    {
        return Client::query();
    }

    /**
     * Get clients based on $params filter
     *
     * @param array $params
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function query($params = [])
    {
        $q = $this->_baseQuery();

        foreach ($params as $k => $v) {
            $q->where($k, $v);
        }

        return $q->get();
    }

    /**
     * Get client by id
     *
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->_baseQuery()
            ->with(['addresses', 'profile'])
            ->whereId($id)
            ->first()
        ;
    }

    /**
     * Create client from formatted data
     *
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $password = str_random(Client::PASSWORD_MIN_LENGTH);

        $user = Client::create(array_merge($data['client'], [
            'password' => Hash::make($password),
        ]));

        $role = Role::whereName(Role::CLIENT)->first();
        $user->roles()->attach($role);

        $profile = new Profile($data['profile']);
        $profile->user()->associate($user);
        $profile->save();

        foreach ($data['addresses'] as $address) {
            $address = new Address($address);
            $address->user()->associate($user);
            $address->save();
        }

        $this->_mailService->send(new RegistrationMail($user, $password));

        return $user;
    }

    /**
     * Format data for client creation from CreateClientRequest request
     *
     * @param CreateClientRequest $request
     * @return array
     */
    public function getDataFromCreateRequest(CreateClientRequest $request)
    {
        $client = $request->only([
            'first_name',
            'last_name',
            'email',
        ]);

        $profile = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'code',
            'commission',
            'comment',
            'type',
        ]);

        $addresses = $this->_getAddressesFromCreateRequest($request);

        return compact('client', 'profile', 'addresses');
    }

    /**
     * Extract addresses data from CreateClientRequest request
     *
     * @param CreateClientRequest $request
     * @return array|mixed
     */
    protected function _getAddressesFromCreateRequest(CreateClientRequest $request)
    {
        $data = $request->get('Addresses');

        if (empty($data)) {
            $data = [
                $request->only([
                    "first_name",
                    "last_name",
                    "street",
                    "house",
                    "apartment",
                    "post_code",
                    "city",
                ]),
            ];
        }

        foreach ($data as &$address) {
            $address = array_merge($address, [
                'active' => 1,
                'country_id' => $request->get('country_id'),
            ]);
        }

        return $data;
    }

    /**
     * Update passed Client instance with provided $data
     *
     * @param Client $client
     * @param $data
     * @return Client
     */
    public function update(Client $client, $data)
    {
        $client->update($data['client']);

        $client->profile()->update($data['profile']);

        return $client;
    }

    /**
     * Format data for client update from UpdateClientRequest request
     *
     * @param UpdateClientRequest $request
     * @return array
     */
    public function getDataFromUpdateRequest(UpdateClientRequest $request)
    {
        $client = $request->only([
            "first_name",
            "last_name",
            "email",
        ]);

        $profile = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'code',
            'commission',
            'comment',
            'type',
        ]);

        return compact('client', 'profile');
    }

    /**
     * Delete client based on pointer
     *
     * Valid pointer can be an integer, an array of integers, or Client class instance.
     *
     * @param $pointer
     */
    public function delete($pointer)
    {
        if (is_int($pointer) && $client = $this->get($pointer)) $this->_delete($client);

        if (is_array($pointer)) {
            foreach ($pointer as $id) {
                $client = $this->get($id);

                if (empty($client)) continue;

                $this->_delete($client);
            }
        }

        if ($pointer instanceof Client) {
            $this->_delete($pointer);
        }
    }

    /**
     * Internal delete method for client
     *
     * @param Client $client
     */
    protected function _delete(Client $client)
    {
        $client->delete();
    }
}