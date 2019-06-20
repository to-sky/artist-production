<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property boolean $active
 * @property int $price_type
 * @property int $price_amount
 * @property string $created_at
 * @property string $updated_at
 */
class PaymentMethod extends Model
{

    const NOT_ACTIVE = 0;
    const ACTIVE = 1;

    const PRICE_TYPE_PERCENTAGE = 0;
    const PRICE_TYPE_STATIC = 1;

    /**
     * @var array
     */
    protected $fillable = ['name', 'active', 'price_type', 'price_amount', 'created_at', 'updated_at'];

    /**
     * Calculate service price based on provided base price
     *
     * @param $basePrice
     * @return float|int
     */
    public function calculateServicePrice($basePrice)
    {
        switch ($this->price_type) {
            case self::PRICE_TYPE_PERCENTAGE: return $basePrice * $this->price_amount / 100;
            case self::PRICE_TYPE_STATIC: return $this->price_amount;
            default: return 0;
        }
    }

    /**
     * String interpretation of service price
     *
     * @return string
     */
    public function getDisplayServicePriceAttribute()
    {
        if (!$this->price_amount) return '';

        $currency = Order::CURRENCY;

        switch ($this->price_type) {
            case self::PRICE_TYPE_PERCENTAGE: return "{$this->price_amount}%";
            case self::PRICE_TYPE_STATIC: return "{$this->price_amount} {$currency}";
            default: return '';
        }
    }
}
