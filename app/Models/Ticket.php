<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Ticket extends Model implements Buyable
{
    use SoftDeletes;

    const AVAILABLE = 0;
    const RESERVED = 1;
    const SOLD = 2;

    protected $fillable = ['barcode', 'amount_printed', 'price', 'status', 'user_id', 'event_id', 'place_id', 'price_id', 'order_id'];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

    public function price()
    {
        return $this->belongsTo('App\Models\Price');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::AVAILABLE);
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['address'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    public function getBuyableDescription($options = null)
    {
        return $this->event->name;
    }

    public function getBuyablePrice($options = null)
    {
        return $this->price;
    }

    /**
     *  User avatar
     *
     * @return mixed
     */
    public function getAddressAttribute()
    {
        return $this->event->hall->name . ', '
            . $this->event->hall->building->address . ', '
            . $this->event->hall->building->city->name;
    }
}
