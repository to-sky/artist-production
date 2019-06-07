<?php

namespace App\Services;


use App\Http\Requests\ProcessCheckoutRequest;
use App\Mail\AccountDetails;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mail;

class UserService
{
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
}