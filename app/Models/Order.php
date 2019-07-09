<?php

namespace App\Models;

use App\Services\TicketService;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'expired_at'];

    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_CANCELED = 2;
    const STATUS_RESERVE = 3;
    const STATUS_REALIZATION = 4;

    const REALIZATION_COMMISSION = 0;
    const REALIZATION_DISCOUNT = 1;

    const CURRENCY = 'EUR';

    protected static function boot() {
        parent::boot();

        static::deleting(function($order) {
            $order->tickets->each(function($ticket) {
                $ticketService = new TicketService();

                $ticketService->freeTicketFromOrder($ticket);
            });
        });
    }

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'expired_at',
        'tax',
        'discount',
        'final_price',
        'subtotal',
        'paid_bonuses',
        'paid_cash',
        'payment_type',
        'delivery_type',
        'delivery_status',
        'shipping_price',
        'shipping_status',
        'shipping_zone_id',
        'payment_method_id',
        'service_price',
        'comment',
        'paid_at',
        'payer_id',
        'manager_id'
    ];

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
    public function manager()
    {
        return $this->belongsTo('App\Models\User', 'manager_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payer()
    {
        return $this->belongsTo('App\Models\User', 'payer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipping()
    {
        return $this->belongsTo('App\Models\Shipping');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }

    public function invoice()
    {
        return $this->hasMany('App\Models\Invoice');
    }

    /**
     * Set attribute to datetime format
     * @param $input
     */
    public function setPaidAtAttribute($input)
    {
        if(!is_null($input)) {
            $this->attributes['paid_at'] = Carbon::createFromFormat(config('admin.date_format') . ' ' . config('admin.time_format'), $input)->format('Y-m-d H:i:s');
        }else{
            $this->attributes['paid_at'] = '';
        }
    }

    /**
     * Get attribute from datetime format
     * @param $input
     *
     * @return string
     */
    public function getPaidAtAttribute($input)
    {
        if(!is_null($input)) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $input)->format(config('admin.date_format') . ' ' .config('admin.time_format'));
        }else{
            return '';
        }
    }

    public function shippingZone()
    {
        return $this->belongsTo('App\Models\ShippingZone');
    }

    public function getEventNamesAttribute()
    {
        $eventIds = $this->tickets()->pluck('event_id');

        if (empty($eventIds)) return '';

        $eventsNames = Event::whereIn('id', $eventIds)->pluck('name');

        return join(', ', $eventsNames->toArray());
    }

    public function getEventsAttribute()
    {
        $eventIds = $this->tickets()->distinct()->pluck('event_id');

        return Event::find($eventIds);
    }

    public function getTicketsCountAttribute()
    {
        return $this->tickets()->count();
    }

    public function getDisplayStatusAttribute()
    {
        $statuses = $this->_displayStatuses();

        return $statuses[$this->status] ?? array_first($statuses);
    }

    protected function _displayStatuses() {
        return [
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_CONFIRMED => __('Confirmed'),
            self::STATUS_CANCELED => __('Cancelled'),
        ];
    }

    public function getReservationDate()
    {
        /** @var null|Carbon $date */
        $date = null;
        foreach ($this->tickets as $ticket) {
            if (empty($date) || $date->greaterThan($ticket->reserved_to)) {
                $date = $ticket->reserved_to;
            }
        }

        return $date;
    }

    /**
     * Get total price of order
     *
     * @return int
     */
    public function getTotalAttribute()
    {
        return $this->subtotal + $this->shipping_price + $this->service_price;
    }
}
