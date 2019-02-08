<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Observers\UserActionsObserver;




class Price extends Model {

    

    

    protected $table    = 'prices';
    
    protected $fillable = [
          'type',
          'price'
    ];
    

    public static function boot()
    {
        parent::boot();

        Price::observe(new UserActionsObserver);
    }
    
    
    
    
}