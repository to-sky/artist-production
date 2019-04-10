<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = [
        'x', 'y', 'is_bold', 'is_italic', 'layer', 'rotation', 'hall_id'
    ];

    public function hall()
    {
        return $this->belongsTo('App\Models\Hall');
    }
}
