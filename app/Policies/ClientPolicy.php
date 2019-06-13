<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the list of clients.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the client.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Client  $client
     * @return mixed
     */
    public function view(User $user, Profile $client)
    {
        return true;
    }

    /**
     * Determine whether the user can create clients.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the client.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Client  $client
     * @return mixed
     */
    public function update(User $user, Profile $client)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the client.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Client  $client
     * @return mixed
     */
    public function delete(User $user, Profile $client)
    {
        //
    }

    /**
     * Determine whether the user can restore the client.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Client  $client
     * @return mixed
     */
    public function restore(User $user, Profile $client)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the client.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Client  $client
     * @return mixed
     */
    public function forceDelete(User $user, Profile $client)
    {
        //
    }
}
