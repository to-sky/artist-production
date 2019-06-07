<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shipping extends Model
{

    const STATUS_IN_PROCESSING = 0;
    const STATUS_DISPATCHED = 1;
    const STATUS_DELIVERED = 2;
    const STATUS_RETURNED = 3;

    const TYPE_DELIVERY = 0;
    const TYPE_EMAIL = 1;

    protected $fillable = [
        'name', 'is_default'
    ];

    public function shippingZones()
    {
        return $this->hasMany('App\Models\ShippingZone');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function setDefault()
    {
        static::where('is_default', true)->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    public function getPriceAttribute()
    {
        $user = Auth::user();

        $country = $user->client->country_id;

        $price = 0;
        $allWorldPrice = 0;

        foreach ($this->shipping_zones as $shipping_zone) {
            $countryIds = $shipping_zone->getCountryIds();
            if (is_array($countryIds)) {
                $shifted = array_shift($countryIds);
                if ($shifted == 0) {
                    $allWorldPrice = $shipping_zone->price;
                } else {
                    if (in_array($country, $shipping_zone->getCountryIds())) {
                        $price = $shipping_zone->price;
                    }
                }
            }
        }

        return $price == 0 ? $allWorldPrice : $price;
    }
}
