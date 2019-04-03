<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    const WORLD = 0;

    /**
     * @var array
     */
    protected $fillable = ['name', 'kartina_id'];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function shipping_zones()
    {
        return $this->belongsToMany(ShippingZone::class);
    }
}
