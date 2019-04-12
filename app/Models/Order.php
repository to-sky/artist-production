<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_CANCELED = 2;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'status', 'expired_at', 'tax', 'discount', 'final_price', 'paid_bonuses', 'paid_cash', 'payment_type', 'delivery_type', 'delivery_status', 'comment', 'paid_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
