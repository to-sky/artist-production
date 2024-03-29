<?php

namespace App\Models;

use App\Services\InvoiceService;
use App\Services\TicketService;
use App\Traits\FilesMorphTrait;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use SoftDeletes, FilesMorphTrait;

    const ENTITY_TYPE = 'order';

    protected $entity_type;

    protected static $ticketService;

    protected static $invoiceService;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'expired_at',
        'paid_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->entity_type = static::ENTITY_TYPE;

        self::$ticketService = new TicketService();
        self::$invoiceService = new InvoiceService();
    }

    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_CANCELED = 2;
    const STATUS_RESERVE = 3;
    const STATUS_REALIZATION = 4;

    const CURRENCY = 'EUR';

    protected static function boot() {
        parent::boot();

        // Add tickets to order
        // Generate provisional invoice
        static::created(function ($order) {
            self::$ticketService->attachCartToOrder($order);
            self::$invoiceService->store($order);
        });

        // Generate final invoice after order set confirmed
        static::updated(function ($order) {
            if($order->isDirty('status')){
                if ($order->status == Order::STATUS_CONFIRMED && is_null($order->final_invoice_id)) {
                    self::$invoiceService->store($order, 'final');
                }
            }
        });

        // Free tickets from order
        static::deleting(function($order) {
            $order->tickets->each(function($ticket) {
                self::$ticketService->freeTicketFromOrder($ticket);
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
        'shipping_type',
        'shipping_status',
        'shipping_price',
        'shipping_zone_id',
        'payment_method_id',
        'service_price',
        'comment',
        'paid_at',
        'payer_id',
        'manager_id',
        'realizator_commission',
        'realizator_percent',
        'provisional_invoice_id',
        'final_invoice_id'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shippingAddress()
    {
        return $this->hasOne('App\Models\ShippingAddress');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billingAddress()
    {
        return $this->hasOne('App\Models\BillingAddress');
    }

    public function provisionalInvoice()
    {
        return $this->belongsTo('App\Models\File', 'provisional_invoice_id', 'id');
    }

    public function finalInvoice()
    {
        return $this->belongsTo('App\Models\File', 'final_invoice_id', 'id');
    }

    /**
     * Get invoice final or provisional
     *
     * @param $tag
     * @return mixed
     */
    public function getInvoice($tag)
    {
        $type = $tag == 'final' ? 'finalInvoice' : 'provisionalInvoice';

        return $this->invoices->where('file_id', $this->$type->id ?? 0)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice')->orderBy('created_at');
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

    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\PaymentMethod');
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
            self::STATUS_RESERVE => __('Reserve'),
            self::STATUS_REALIZATION => __('Realization'),
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

    public function getCompositeDiscountAttribute()
    {
        return $this->tickets->map(function ($ticket) {
            return $ticket->discount;
        })->sum() + $this->discount;
    }

    /**
     * Get sum all tickets with discount
     *
     * @return int
     */
    public function getTicketsPriceWithDiscountAttribute()
    {
        return $this->tickets->map(function ($ticket) {
            return $ticket->price - $ticket->discount;
        })->sum();
    }

    /**
     * Get total price of order
     *
     * @return int
     */
    public function getTotalAttribute()
    {
        return $this->getTicketsPriceWithDiscountAttribute() + $this->shipping_price + $this->service_price;
    }

    public function getDisplayShippingStatusAttribute()
    {
        $statuses = $this->_displayShippingStatus();

        return $statuses[$this->shipping_status] ?? array_first($statuses);
    }

    protected function _displayShippingStatus() {
        return [
            Shipping::STATUS_NOT_SET => __('Not set'),
            Shipping::STATUS_IN_PROCESSING => __('In processing'),
            Shipping::STATUS_DISPATCHED => __('Dispatched'),
            Shipping::STATUS_DELIVERED => __('Delivered'),
            Shipping::STATUS_NOT_DELIVERED => __('Not delivered'),
        ];
    }


    public function getDisplayShippingTypeAttribute()
    {
        $types = $this->_displayShippingType();

        return $types[$this->shipping_type] ?? $types[Shipping::TYPE_OFFICE];
    }

    protected function _displayShippingType() {
        return [
            Shipping::TYPE_EMAIL => __('E-ticket'),
            Shipping::TYPE_OFFICE => __('Evening ticket office'),
            Shipping::TYPE_POST => __('Post delivery'),
        ];
    }

    /**
     * Check if order is paid
     *
     * @return bool
     */
    public function is_paid()
    {
        return is_null($this->paid_at) ? false : true;
    }

    /**
     * Check if order is confirmed
     *
     * @return bool
     */
    public function is_confirmed()
    {
        return $this->status == self::STATUS_CONFIRMED;
    }

    /**
     * Get hall name
     *
     * @return string
     */
    public function getHallNameAttribute()
    {
        return $this->tickets->first()
            ? $this->tickets->first()->event()->withTrashed()->first()->hall->name
            : '';
    }
}
