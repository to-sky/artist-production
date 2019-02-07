<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $zones_id
 * @property string $row_num
 * @property string $place_num
 * @property string $place_text
 * @property string $help_text
 * @property string $created_at
 * @property string $updated_at
 * @property Zone $zone
 * @property Price[] $prices
 */
class Place extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['zones_id', 'row_num', 'place_num', 'place_text', 'help_text', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone', 'zones_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function prices()
    {
        return $this->belongsToMany('App\Models\Price', 'price_place');
    }
}
