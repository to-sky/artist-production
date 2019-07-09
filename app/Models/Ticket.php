<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Keygen\Keygen;

/**
 * Ticket model
 *
 * @property int $id
 * @property int $barcode
 * @property int $amount_printed
 * @property float $price
 * @property int $status
 * @property int $user_id
 * @property User $user
 * @property int $event_id
 * @property Event $event
 * @property int $place_id
 * @property Place $place
 * @property int $order_id
 * @property Order $order
 * @property string $address
 * @property PriceGroup $price_group
 * @property bool $is_sitting_place
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $reserved_to
 * @property Carbon $deleted_at
 *
 * Class Ticket
 * @package App\Models
 */
class Ticket extends Model implements Buyable
{
    use SoftDeletes;

    const AVAILABLE = 0;
    const RESERVED = 1;
    const SOLD = 2;

    protected static $barcodes = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'reserved_to',
        'deleted_at',
    ];

    protected $fillable = ['barcode', 'amount_printed', 'price', 'status', 'user_id', 'event_id', 'place_id', 'price_id', 'reserved_to', 'order_id'];

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

    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

    public function price()
    {
        return $this->belongsTo('App\Models\Price');
    }

    public function priceGroup()
    {
        return $this->belongsTo('App\Models\PriceGroup');
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
        $discount = $this->priceGroup()->value('discount') ?: 0;

        return (100 - $discount) * $this->price()->value('price') / 100;
    }

    public function getIsSittingPlaceAttribute()
    {
        $place = $this->place;

        return !!$place->row && !!$place->num;
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
}
