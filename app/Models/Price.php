<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['price', 'color', 'event_id'];

    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    public function ticket()
    {
        return $this->hasMany('App\Models\Ticket');
    }
}
