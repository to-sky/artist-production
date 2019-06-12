<?php

namespace App\Services;


use App\Http\Requests\ProcessCheckoutRequest;
use App\Mail\AccountDetails;
use App\Models\Address;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mail;

class UserService
{
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

        if($authenticate) auth()->loginUsingId($user->id);

        Mail::to($user->email)->send(new AccountDetails($user, $password));

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