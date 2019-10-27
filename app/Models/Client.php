<?php

namespace App\Models;

/**
 * Class Client alias for users with role client
 *
 * @package App
 */
class Client extends User
{
    protected $table = 'users';

    public function newQuery()
    {
        return parent::newQuery()->whereHas('roles', function ($q) {
            $q->whereName(Role::CLIENT);
        });
    }

    protected function getMainAddressAttribute()
    {
        $addr = $this->addresses()->active()->first();

        if ($addr) return $addr->full;

        return '-';
    }
}
