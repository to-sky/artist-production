<?php

namespace App\Models;

use App\Traits\FilesMorphTrait;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Observers\UserActionsObserver;
use Carbon\Carbon;

class Event extends Model
{
    use FilesMorphTrait;

    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

    const ENTITY_TYPE = 'events';

    function __construct(array $attributes = [])
    {
        $this->entity_type = static::ENTITY_TYPE;

        parent::__construct($attributes);
    }

    protected $fillable = [
          'name',
          'date',
          'is_active',
          'hall_id',
          'event_image_id',
          'free_pass_logo_id'
    ];

    public static function boot()
    {
        parent::boot();

        Event::observe(new UserActionsObserver);
    }

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }

    public function eventImage()
    {
        return $this->belongsTo('App\Models\File', 'event_image_id');
    }

    public function freePassLogo()
    {
        return $this->belongsTo('App\Models\File', 'free_pass_logo_id');
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
