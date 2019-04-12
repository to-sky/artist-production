<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function setDefault()
    {
        static::where('is_default', true)->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }
}
