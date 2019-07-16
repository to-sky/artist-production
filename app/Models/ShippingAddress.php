<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $country_id
 * @property int $order_id
 * @property string $first_name
 * @property string $last_name
 * @property string $street
 * @property string $house
 * @property string $apartment
 * @property string $post_code
 * @property string $city
 * @property string $created_at
 * @property string $updated_at
 * @property Country $country
 * @property Order $order
 * @property Order[] $orders
 */
class ShippingAddress extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'country',
        'order_id',
        'first_name',
        'last_name',
        'street',
        'house',
        'apartment',
        'post_code',
        'city',
        'created_at',
        'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }


    /**
     * Full name accessor
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

/**
* Return full address
*
* @return string
*/
    public function getFullAttribute()
    {
        $parts = [];
        $parts[] = $this->full_name;
        $parts[] = $this->country;
        $parts[] = $this->city;
        $parts[] = $this->street;
        $parts[] = $this->post_code;

        return join(', ', $parts);
    }
}
