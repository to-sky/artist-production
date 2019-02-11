<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $buildings_id
 * @property string $name
 * @property string $accounting_code
 * @property string $created_at
 * @property string $updated_at
 * @property Building $building
 * @property Place[] $places
 */
class Hall extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['buildings_id', 'name', 'accounting_code', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buildings()
    {
        return $this->belongsTo('App\Models\Building', 'buildings_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function places()
    {
        return $this->hasMany('App\Models\Place', 'halls_id');
    }
}
