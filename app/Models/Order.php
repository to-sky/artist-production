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
    protected $fillable = [
        'user_id',
        'status',
        'expired_at',
        'tax',
        'discount',
        'final_price',
        'subtotal',
        'paid_bonuses',
        'paid_cash',
        'payment_type',
        'delivery_type',
        'delivery_status',
        'shipping_price',
        'shipping_status',
        'shipping_zone_id',
        'payment_method_id',
        'service_price',
        'comment',
        'paid_at',
    ];

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

    public function invoice()
    {
        return $this->hasMany('App\Models\Invoice');
    }

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

    public function shippingZone()
    {
        return $this->belongsTo('App\Models\ShippingZone');
    }

    public function getEventAttribute()
    {
        $ticket = $this->tickets()->first();

        if (empty($ticket)) return null;

        return $ticket->event()->first();
    }

    public function getTicketsCountAttribute()
    {
        return $this->tickets()->count();
    }

    public function getDisplayStatusAttribute()
    {
        $statuses = $this->_displayStatuses();

        return $statuses[$this->status] ?? array_first($statuses);
    }

    protected function _displayStatuses() {
        return [
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_CONFIRMED => __('Confirmed'),
            self::STATUS_CANCELED => __('Cancelled'),
        ];
    }

    public function getTotalAttribute()
    {
        return $this->subtotal + $this->tax + $this->shipping_price;
    }
}
