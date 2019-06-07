<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Keygen\Keygen;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Ticket extends Model implements Buyable
{
    use SoftDeletes;

    const AVAILABLE = 0;
    const RESERVED = 1;
    const SOLD = 2;

    protected static $barcodes = [];

    protected $fillable = ['barcode', 'amount_printed', 'price', 'status', 'user_id', 'event_id', 'place_id', 'price_id'];

    public function __construct(array $attributes = [])
    {
        if (! $this->barcode){
            $this->generateUniqueBarcode();
        }

        parent::__construct($attributes);
    }

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
     * Generate unique barcode
     */
    public function generateUniqueBarcode()
    {
        if (! count(self::$barcodes)) {
            self::$barcodes = \DB::table('tickets')
                ->whereNotNull('barcode')
                ->groupBy('barcode')
                ->pluck('barcode')
                ->toArray()
            ;

            self::$barcodes = array_flip(self::$barcodes);
        }

        do {
            $barcode = Keygen::numeric(12)->generate();
        } while (isset(self::$barcodes[$barcode]));

        self::$barcodes[$barcode] = 1;

        $this->setRawAttributes([
            'barcode' => $barcode,
        ]);
    }

    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    public function getBuyableDescription($options = null)
    {
        return $this->name;
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
