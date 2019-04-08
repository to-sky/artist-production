<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = ['name', 'address', 'city_id', 'kartina_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function halls()
    {
        return $this->hasMany('App\Models\Hall');
    }
}
