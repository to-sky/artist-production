<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */

    protected $table    = 'prices';

    protected $fillable = [
];


    public static function boot()
    {
        parent::boot();

        Price::observe(new UserActionsObserver);
    }




}
