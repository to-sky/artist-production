<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Observers\UserActionsObserver;
use Carbon\Carbon;


class Event extends Model
{
    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

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
        return $this->belongsTo('App\Models\Hall');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\Price');
    }

    public function priceGroups()
    {
        return $this->hasMany('App\Models\PriceGroup');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }

    /**
     * Set attribute to datetime format
     * @param $input
     */
    public function setDateAttribute($input)
    {
        if($input != '') {
            $this->attributes['date'] = Carbon::createFromFormat(
                config('admin.date_format') . ' ' . config('admin.time_format_hm'), $input
            )->format('Y-m-d H:i:s');
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

    /**
     * Set is_active attribute
     *
     * @param $input
     */
    public function setIsActiveAttribute($input)
    {
        $this->attributes['is_active'] = empty($input)
            ? self::NOT_ACTIVE
            : self::ACTIVE;
    }
}
