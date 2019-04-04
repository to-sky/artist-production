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
 * @property Client $client
 * @property Country $country
 */
class Address extends Model
{

    const NOT_ACTIVE = 0;
    const ACTIVE = 1;

    /**
     * @var array
     */
    protected $fillable = ['country_id', 'client_id', 'first_name', 'last_name', 'street', 'house', 'apartment', 'post_code', 'city', 'active'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
