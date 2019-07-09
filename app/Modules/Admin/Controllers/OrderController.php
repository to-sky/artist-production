<?php

namespace App\Modules\Admin\Controllers;

use App\Models\{Address, Country, Order, Event, PaymentMethod, Shipping, ShippingZone, Ticket, User};
use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Admin\Services\RedirectService;
use App\Services\ShippingService;
use App\Services\TicketService;
use App\Modules\Admin\Requests\CreateOrderRequest;
use App\Modules\Admin\Requests\UpdateOrderRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Prologue\Alerts\Facades\Alert;

class OrderController extends AdminController
{

    protected $ticketService;
    protected $shippingService;

    public function __construct(
        RedirectService $redirectService,
        TicketService $ticketService,
        ShippingService $shippingService
    )
    {
        $this->ticketService = $ticketService;
        $this->shippingService = $shippingService;

        parent::__construct($redirectService);
    }

    /**
	 * Display a listing of orders
	 *
     * @return \Illuminate\View\View
	 */
	public function index()
    {
		return view('Admin::order.index', [
		    'orders' => Order::all()
        ]);
	}

    /**
     * Show the form for creating a new order
     *
     * @return \Illuminate\View\View
     */
	public function create()
	{
	    return view('Admin::order.create', [
	        'events' => Event::all(),
            'users' => User::all(),
            'ticketsData' => $this->getTicketData()
        ]);
	}

    /**
     * Get event data to order popup widget
     *
     * @param Event $event
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function widgetContent(Event $event)
    {
        return view('Admin::order.partials.modals._popup_widget', compact('event'));
    }

    /**
     * Update tickets table
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateTicketsTable()
    {
        $ticketsData = $this->getTicketData();

        return view('Admin::order.partials._tickets_table', compact('ticketsData'));
	}

    /**
     * Generate tickets data
     *
     * @return array
     */
    public function getTicketData()
    {
        $ticketsData = [];
        $this->ticketService->getCartTickets()
            ->where('status', Ticket::RESERVED)
            ->map(function ($ticket) use (&$ticketsData) {
                return $ticketsData[$ticket->event->name][] = $ticket;
            });

        return $ticketsData;
	}

    /**
     * Store a newly created order in storage.
     *
     * @param Request|Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(Request $request)
	{
        switch ($request->order_type) {
            case 'sale':
                $this->sale($request);
            break;
            case 'realization':
                $this->realization($request);
            break;
            case 'reserve':
                $this->reserve($request);
            break;
        }

        Alert::success(trans('Admin::admin.controller-successfully_created', ['item' => trans('Admin::models.OrderController')]))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

    /**
     * Update tickets status, price and associate to order
     *
     * @param array $tickets
     * @param $orderId
     * @param $payerId
     * @param $ticketStatus
     * @return \Illuminate\Support\Collection
     */
    public function updateTickets(array $tickets, $orderId, $payerId, $ticketStatus)
    {
        $response = collect();
        foreach ($tickets as $id => $discount) {
            $ticket = Ticket::find($id);

            $ticketPrice = $ticket->getBuyablePrice();
            $ticketDiscount = (float) $discount['discount'];
            if ($ticketDiscount) {
                $ticketPrice -= $ticketDiscount;
            }


            $eventDate = $ticket->event->date;
            $diffDate = $eventDate->diffInDays(Carbon::now());

            $reservedTo = $diffDate >= 14
                ? Carbon::now()->addDays(14)
                : $eventDate;

            $ticket->update([
                'price' => $ticketPrice,
                'status' => $ticketStatus,
                'discount' => $ticketDiscount,
                'user_id' => $payerId,
                'order_id' => $orderId,
                'reserved_to' => $reservedTo
            ]);

            $response->push($ticket);
        }

        return $response;
    }

    /**
     * Sale order
     *
     * @param Request $request
     * @return mixed
     */
    public function sale(Request $request)
    {
        $clientId = $request->user_id ?? Auth::id();
        $order = Order::create([
            'status' => Order::STATUS_CONFIRMED,
            'paid_at' => Carbon::now(),
            'user_id' => $clientId,
            'payer_id' => Auth::id(),
            'manager_id' => Auth::id()
        ]);

        $subtotal = $this->updateTickets(
            $request->tickets,
            $order->id,
            $clientId,
            Ticket::SOLD
        )->sum('price');

        $discount = $request->main_discount;
        $subtotal = $subtotal - $discount;

        $order->update([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'paid_cash' => $subtotal,
        ]);

        return $order;
	}

    /**
     * Order under realization
     *
     * @param $request
     * @return mixed
     */
    public function realization(Request $request)
    {
        $clientId = $request->user_id ?? Auth::id();
        $order = Order::create([
            'status' => Order::STATUS_REALIZATION,
            'user_id' => $clientId,
            'payer_id' => Auth::id(),
            'manager_id' => Auth::id(),
            'comment' => $request->comment,
            'expired_at' => Carbon::now()->addDays(14)
        ]);

        $subtotal = $this->updateTickets(
            $request->tickets,
            $order->id,
            $clientId,
            Ticket::RESERVED
        )->sum('price');

        $discount = $request->main_discount;
        $subtotal = $subtotal - $discount;
        $realizationPercent = $subtotal * ($request->realization_percent / 100);
        if ($request->realization_method == Order::REALIZATION_COMMISSION) {
            $subtotal = $subtotal + $realizationPercent;
        } else {
            $subtotal = $subtotal - $realizationPercent;
            $discount = $discount + $realizationPercent;
        }

        $order->update([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'paid_cash' => $subtotal
        ]);

        return $order;
	}

    /**
     * Reserve order
     *
     * @param $request
     * @return mixed
     */
    public function reserve(Request $request)
    {
        $clientId = $request->user_id ?? Auth::id();

        $data = [
            'status' => Order::STATUS_RESERVE,
            'user_id' => $clientId,
            'payer_id' => Auth::id(),
            'manager_id' => Auth::id(),
            'comment' => $request->comment,
            'expired_at' => Carbon::now()->addDays(14)
        ];

        if ($request->shipping_type == 'post') {
            $address = Address::find($request->address_id);
            $shippingZones = $this->shippingService->getShippingOptionsForCountry($address->country);

            $data['shipping_zone_id'] = $request->shipping_zone_id;
            $data['shipping_price'] = min(array_column($shippingZones, 'price'));
            $data['shipping_status'] = Shipping::STATUS_IN_PROCESSING;
            $data['shipping_type'] = Shipping::TYPE_EMAIL;
            $data['payment_method_id'] = PaymentMethod::paymentDelay()->first()->id;
        }

        $order = Order::create($data);

        $this->updateTickets(
            $request->tickets,
            $order->id,
            $clientId,
            Ticket::RESERVED
        )->sum('price');

        return $order;
	}

    /**
     * Get user addresses
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAddresses(Request $request)
    {
        $addresses = Address::whereUserId($request->user_id)
            ->active()
            ->get()
            ->map(function($address) {
                return [
                    'id' => $address->id,
                    'address' => $address->full
                ];
            });

        return response()->json($addresses);
	}

	/**
	 * Show the form for editing the specified order.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$order = Order::find($id);

		return view('Admin::order.edit', compact('order'));
	}

	/**
	 * Update the specified order in storage.
	 *
     * @param UpdateOrderRequest|Request $request
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id, UpdateOrderRequest $request)
	{
		$order = Order::findOrFail($id);

		$order->update($request->all());

        Alert::success(trans('Admin::admin.controller-successfully_created', ['item' => trans('Admin::models.OrderController')]))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

    /**
     * Remove the specified order from storage.
     *
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
	public function destroy(Order $order)
	{
		$order->delete();

		return response()->json(null, 204);
	}

    /**
     * Mass delete function from index page
     *
     * @param Request $request
     * @return mixed
     */
    public function massDelete(Request $request)
    {
        if ($request->get('toDelete') != 'mass') {
            $toDelete = json_decode($request->get('toDelete'));
            Order::destroy($toDelete);
        } else {
            Order::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.orders.index');
    }

}
