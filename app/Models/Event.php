<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Event extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['buildings_id', 'name', 'date', 'is_active', 'ticket_refund_period', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buildings()
    {
        return $this->belongsTo('App\Models\Building', 'buildings_id');
    }

    /**
     * Set attribute to datetime format
     * @param $input
     */
    public function setDateAttribute($input)
    {
        if($input != '') {
            $this->attributes['date'] = Carbon::createFromFormat(config('admin.date_format') . ' ' . config('admin.time_format'), $input)->format('Y-m-d H:i:s');
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
            return Carbon::createFromFormat('Y-m-d H:i:s', $input)->format(config('admin.date_format') . ' ' .config('admin.time_format'));
        }else{
            return '';
        }
    }
}
