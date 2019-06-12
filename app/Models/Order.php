<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_CANCELED = 2;

    const CURRENCY = 'EUR';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'status', 'expired_at', 'tax', 'discount', 'final_price', 'paid_bonuses', 'paid_cash', 'payment_type', 'delivery_type', 'delivery_status', 'comment', 'paid_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
//    protected $appends = ['subTotal'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipping()
    {
        return $this->belongsTo('App\Models\Shipping');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }

    public function shippingZone()
    {
        return $this->belongsTo('App\Models\ShippingZone');
    }

    /**
     * Order subTotal attribute
     */
//    public function getSubTotalAttribute()
//    {
//        $subTotal = 0;
//
//        foreach ($this->tickets() as $ticket) {
//            $subTotal += $ticket->price;
//        }
//
//        return $subTotal;
//    }

    /**
     * Set attribute to datetime format
     * @param $input
     */
    public function setPaidAtAttribute($input)
    {
        if(!is_null($input)) {
            $this->attributes['paid_at'] = Carbon::createFromFormat(config('admin.date_format') . ' ' . config('admin.time_format'), $input)->format('Y-m-d H:i:s');
        }else{
            $this->attributes['paid_at'] = '';
        }
    }

    /**
     * Get attribute from datetime format
     * @param $input
     *
     * @return string
     */
    public function getPaidAtAttribute($input)
    {
        if(!is_null($input)) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $input)->format(config('admin.date_format') . ' ' .config('admin.time_format'));
        }else{
            return '';
        }
    }
}
