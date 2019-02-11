<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'clients';
    
    protected $fillable = [
          'first_name',
          'last_name',
          'email',
          'phone',
          'street',
          'house',
          'city',
          'post_code',
          'comission',
          'code',
          'comment'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['fullname'];
    

    public static function boot()
    {
        parent::boot();

        Client::observe(new UserActionsObserver);
    }

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    
    
    
}