<?php

namespace App\Services;


use App\Http\Requests\ProcessCheckoutRequest;
use App\Http\Requests\ProfileRequest;
use App\Mail\DynamicMails\RegistrationMail;
use App\Models\Address;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $_mailService;

    public function __construct(MailService $mailService)
    {
        $this->_mailService = $mailService;
    }

    /**
     * Create client user from Checkout request
     *
     * @param ProcessCheckoutRequest $request
     * @param bool $authenticate
     * @return User
     */
    public function createFromCheckoutRequest(ProcessCheckoutRequest $request, $authenticate = true)
    {
        $data = $request->only('user');
        $password = str_random(User::PASSWORD_MIN_LENGTH);

        /** @var User $user */
        $user = User::create(array_merge($data['user'], [
            'password' => Hash::make($password),
        ]));

        $role = Role::whereName(Role::CLIENT)->first();
        $user->roles()->attach($role);

        /** @var Profile $profile */
        $profile = Profile::create(array_merge($data['user'], [
            'user_id' => $user->id,
        ]));

        if($authenticate) auth()->loginUsingId($user->id);

        $this->_mailService->send(new RegistrationMail($user, $password));

        return $user;
    }

    public function updateFromProfileRequest(ProfileRequest $request, User $user)
    {
        $data = $request->only('profile');

        $user->fill($data['profile']);
        $user->save();

        $profile = $user->profile;
        $profile->fill($data['profile']);
        $profile->save();

        return $user;
    }

    /**
     * Add address to $user (if empty create unattached address)
     *
     * @param array $data
     * @param User|null $user
     * @return Address
     */
    public function addUserAddress(array $data, User $user = null)
    {
        $address = new Address($data);
        $address->user()->associate($user);
        $address->save();

        return $address;
    }

    /**
     * Compile main address data from checkout request
     *
     * @param ProcessCheckoutRequest $request
     * @return array
     */
    public function extractAddressFromPaymentRequest(ProcessCheckoutRequest $request)
    {
        $user = $request->get('user');
        $address = $request->get('billing_address');

        return array_merge($address, [
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'active' => 1,
        ]);
    }

    /**
     * Compile additional address data from checkout request
     *
     * @param ProcessCheckoutRequest $request
     * @return array
     */
    public function extractAdditionalAddressFromPaymentRequest(ProcessCheckoutRequest $request)
    {
        return array_merge($request->get('other_address'), [
            'active' => 1,
        ]);
    }
}