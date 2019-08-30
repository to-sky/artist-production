<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HallBlueprint extends Model
{
    protected $guarded = [];

    public function placeBlueprints()
    {
        return $this->hasMany(PlaceBlueprint::class);
    }

    public function zoneBlueprints()
    {
        return $this->hasMany(ZoneBlueprint::class);
    }

    public function labelBlueprints()
    {
        return $this->hasMany(LabelBlueprint::class);
    }
}
