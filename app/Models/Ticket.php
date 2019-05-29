<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Keygen\Keygen;

class Ticket extends Model
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
}
