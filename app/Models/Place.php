<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'row', 'num', 'text', 'zone_id', 'hall_id', 'kartina_id', 'template',
        'x', 'y', 'width','height', 'path', 'rotate'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hall()
    {
        return $this->belongsTo('App\Models\Hall');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }
}
