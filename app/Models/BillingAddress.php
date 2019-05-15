<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property string $first_name
 * @property string $last_name
 * @property string $street
 * @property string $house
 * @property string $apartment
 * @property string $post_code
 * @property string $city
 * @property string $country
 * @property string $created_at
 * @property string $updated_at
 * @property Order $order
 */
class BillingAddress extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['order_id', 'first_name', 'last_name', 'street', 'house', 'apartment', 'post_code', 'city', 'country', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
