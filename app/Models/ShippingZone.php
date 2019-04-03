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
        return $this->belongsTo(Shipping::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class);
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
