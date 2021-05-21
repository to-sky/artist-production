<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = [
        'name', 'price', 'shipping_id'
    ];

    public function shipping()
    {
        return $this->belongsTo('App\Models\Shipping');
    }

    public function countries()
    {
        return $this->belongsToMany('App\Models\Country');
    }

    /**
     * Get country ids for shipping zone
     *
     * @return array
     */
    public function getCountryIds()
    {
        $countryIds = $this->countries->map->id->toArray();

        if (empty($countryIds)) {
            return [Country::WORLD];
        }

        return $countryIds;
    }
}
