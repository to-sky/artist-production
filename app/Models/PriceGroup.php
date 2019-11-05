<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceGroup extends Model
{
    protected $fillable = ['name', 'discount', 'event_id'];

    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }
}
