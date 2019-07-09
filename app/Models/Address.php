<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $country_id
 * @property int $client_id
 * @property string $first_name
 * @property string $last_name
 * @property string $street
 * @property string $house
 * @property string $apartment
 * @property string $post_code
 * @property string $city
 * @property boolean $default
 * @property User $user
 * @property Country $country
 * @property string $full
 * @property string $full_name
 * @property string $building_name
 */
class Address extends Model
{

    const NOT_ACTIVE = 0;
    const ACTIVE = 1;

    /**
     * @var array
     */
    protected $fillable = ['country_id', 'user_id', 'first_name', 'last_name', 'street', 'house', 'apartment', 'post_code', 'city', 'active'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * Full name accessor
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getBuildingNameAttribute()
    {
        $building[] = $this->street;
        if ($this->house) $building[] = $this->house;
        if ($this->apartment) $building[] = __('Ap. :apartment', ['apartment' => $this->apartment]);
        return join(' ', $building);
    }

    /**
     * Return full address
     *
     * @return string
     */
    public function getFullAttribute()
    {
        $parts = [];
        $parts[] = $this->full_name;
        $parts[] = $this->country->name;
        $parts[] = $this->city;
        $parts[] = $this->building_name;
        $parts[] = $this->post_code;

        return join(', ', $parts);
    }

    public function scopeActive($query)
    {
        return $query->where('active', self::ACTIVE);
    }
}
