<?php

namespace App\Models;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\UserRoleTrait;
use App\Traits\FilesMorphTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property boolean $active
 * @property string $created_at
 * @property string $updated_at
 * @property string $remember_token
 * @property \App\Models\Address[] $addresses
 * @property \App\Models\Profile $profile
 * @property \App\Models\Order[] $orders
 * @property-read string $display_id
 */
class User extends Authenticatable implements AuthenticatableContract
{
    use Authorizable, UserRoleTrait, Notifiable, FilesMorphTrait;

    const PASSWORD_MIN_LENGTH =  6;

    const ENTITY_TYPE = 'users';

    const NOT_ACTIVE = 0;
    const ACTIVE = 1;

    function __construct(array $attributes = [])
    {
        $this->entity_type = static::ENTITY_TYPE;
        parent::__construct($attributes);
    }

    /**
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'active', 'password'];

    protected $hidden = ['password', 'remember_token'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar', 'fullname', 'role'];

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address', 'user_id');
    }

    public function profile()
    {
        return $this->hasOne('App\Models\Profile', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'user_id');
    }

    /**
     *  User avatar
     *
     * @return mixed
     */
    public function getAvatarAttribute()
    {
        return $this->files()->first();
    }

    /**
     * User full name
     *
     * @return string
     */
    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * User role
     *
     * @return mixed
     */
    public function getRoleAttribute()
    {
        return $this->roles()->first();
    }

    /**
     * Returns user id in 6 digit format with prepending zeroes (i.e.: 000105)
     *
     * @return string
     */
    public function getDisplayIdAttribute()
    {
        return sprintf('%06d', $this->id);
    }
}
