<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Observers\UserActionsObserver;
use Carbon\Carbon;


class Event extends Model {

    protected $fillable = [
          'name',
          'date',
          'is_active',
          'hall_id'
    ];

    public static function boot()
    {
        parent::boot();

        Event::observe(new UserActionsObserver);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    /**
     * Set attribute to datetime format
     * @param $input
     */
    public function setDateAttribute($input)
    {
        if($input != '') {
            $this->attributes['date'] = Carbon::createFromFormat('Y-m-d H:i', $input)->format('Y-m-d H:i:s');
        }else{
            $this->attributes['date'] = '';
        }
    }

    /**
     * Get attribute from datetime format
     * @param $input
     *
     * @return string
     */
    public function getDateAttribute($input)
    {
        if($input != '0000-00-00') {
            return Carbon::createFromFormat('Y-m-d H:i:s', $input);
        }else{
            return '';
        }
    }
}
