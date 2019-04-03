<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'name', 'is_default'
    ];

    public function shipping_zones()
    {
        return $this->hasMany(ShippingZone::class);
    }

    public function setDefault()
    {
        static::where('is_default', true)->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }
}
