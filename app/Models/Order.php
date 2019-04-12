<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

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
    protected $appends = ['subTotal'];

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
     * Order subTotal attribute
     */
    public function getSubTotalAttribute()
    {
        $subTotal = 0;

        foreach ($this->tickets() as $ticket) {
            $subTotal += $ticket->price;
        }

        return $subTotal;
    }
}
