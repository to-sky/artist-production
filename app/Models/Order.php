<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property string $expired_at
 * @property float $tax
 * @property float $discount
 * @property float $final_price
 * @property int $paid_bonuses
 * @property string $paid_cash
 * @property string $payment_type
 * @property string $delivery_type
 * @property string $delivery_status
 * @property string $comment
 * @property string $paid_at
 * @property User $user
 */
class Order extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'status', 'expired_at', 'tax', 'discount', 'final_price', 'paid_bonuses', 'paid_cash', 'payment_type', 'delivery_type', 'delivery_status', 'comment', 'paid_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
