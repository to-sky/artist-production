<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $buildings_id
 * @property string $name
 * @property string $date
 * @property boolean $is_active
 * @property int $tickets_return_expires
 * @property string $created_at
 * @property string $updated_at
 * @property Building $building
 */
class Event extends Model
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
    protected $fillable = ['buildings_id', 'name', 'date', 'is_active', 'tickets_return_expires', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building()
    {
        return $this->belongsTo('App\Models\Building', 'buildings_id');
    }
}
