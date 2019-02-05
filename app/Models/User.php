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
 * @property string $email
 * @property string $password
 * @property boolean $active
 * @property string $created_at
 * @property string $updated_at
 * @property string $remember_token
 */
class User extends Authenticatable implements AuthenticatableContract
{
    use Authorizable, UserRoleTrait, Notifiable, FilesMorphTrait;

    const PASSWORD_MIN_LENGTH =  6;

    const ENTITY_TYPE = 'users';

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
}