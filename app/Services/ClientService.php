<?php

namespace App\Services;


use App\Mail\DynamicMails\RegistrationMail;
use App\Models\Address;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Modules\Admin\Requests\CreateClientRequest;
use Illuminate\Database\Eloquent\Builder;
use Hash;

class ClientService
{
    /** @var MailService */
    protected $_mailService;

    public function getModelClass()
    {
        return User::class;
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
        return User
            ::with('profile')
            ->whereHas('roles', function ($qq) {
                $qq->whereName(Role::CLIENT);
            })
        ;
    }

    public function query($params = [])
    {
        $q = $this->_baseQuery();

        foreach ($params as $k => $v) {
            $q->where($k, $v);
        }

        return $q->get();
    }

    public function get($id)
    {
        return $this->_baseQuery()
            ->with(['addresses', 'profile'])
            ->whereId($id)
            ->first()
        ;
    }

    public function create($data)
    {
        $password = str_random(User::PASSWORD_MIN_LENGTH);

        $user = User::create(array_merge($data['user'], [
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

    public function getDataFromCreateRequest(CreateClientRequest $request)
    {
        $user = $request->only([
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

        return compact('user', 'profile', 'addresses');
    }

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
}