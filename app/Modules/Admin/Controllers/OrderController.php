<?php

namespace App\Modules\Admin\Controllers;

use App\Models\{Address,
    Country,
    Order,
    Event,
    PaymentMethod,
    Role,
    Shipping,
    ShippingAddress,
    ShippingZone,
    Ticket,
    User};
use App\Modules\Admin\Controllers\AdminController;
use App\Modules\Admin\Services\RedirectService;
use App\Services\ClientService;
use App\Services\ShippingService;
use App\Services\TicketService;
use App\Modules\Admin\Requests\CreateOrderRequest;
use App\Modules\Admin\Requests\UpdateOrderRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Documents\Invoices\OrderInvoiceDocument;

use Illuminate\Support\Facades\Auth;
use Prologue\Alerts\Facades\Alert;
use SebastianBergmann\ObjectReflector\ObjectReflector;

class OrderController extends AdminController
{
    protected $ticketService;

    protected $shippingService;

    protected $clientService;

    public function __construct(
        RedirectService $redirectService,
        TicketService $ticketService,
        ShippingService $shippingService,
        ClientService $clientService
    )
    {
        $this->ticketService = $ticketService;
        $this->shippingService = $shippingService;
        $this->clientService = $clientService;

        parent::__construct($redirectService);
    }

    /**
	 * Display a listing of orders
	 *
     * @return \Illuminate\View\View
	 */
	public function index()
    {
        $q = Order::query();

        $user = auth()->user();
        if ($user->hasRole(Role::PARTNER)) {
            $q->whereManagerId($user->id);
        }
        $orders = $q->get();

        $invoiceDocument = new OrderInvoiceDocument($q->find(159));
        dd($invoiceDocument->getFile('provisional'));
//        dump($invoice->link('provisional'));
//        dump($invoice->link('final'));


        $paymentMethods = PaymentMethod::all()
            ->pluck('name', 'id')
            ->prepend(__('Evening ticket office'), '');

		return view('Admin::order.index', compact('orders', 'paymentMethods'));
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
            'users' => $this->clientService->query(),
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
            case 'sale': $order = $this->sale($request);
            break;
            case 'realization': $order = $this->realization($request);
            break;
            case 'reserve': $order = $this->reserve($request);
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
            $eventDate = $ticket->event->date;
            $diffDate = $eventDate->diffInDays(Carbon::now());

            $reservedTo = $diffDate >= 14
                ? Carbon::now()->addDays(14)
                : $eventDate;

            $ticket->update([
                'price' => $ticket->getBuyablePrice(),
                'status' => $ticketStatus,
                'discount' => $discount['discount'],
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
        $order = Order::create([
            'status' => Order::STATUS_CONFIRMED,
            'paid_at' => Carbon::now(),
            'user_id' => $request->user_id,
            'payer_id' => Auth::id(),
            'manager_id' => Auth::id(),
            'shipping_type' => Shipping::TYPE_OFFICE
        ]);

        $clientId = $request->user_id ?? Auth::id();
        $ticketsPrice = $this->updateTickets(
            $request->tickets,
            $order->id,
            $clientId,
            Ticket::SOLD
        )->sum('price');

        $orderDiscount = $request->main_discount;
        $paid = $order->ticketsPriceWithDiscount - $orderDiscount;

        $order->update([
            'subtotal' => $ticketsPrice,
            'discount' => $orderDiscount,
            'paid_cash' => $paid,
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
            'manager_id' => Auth::id(),
            'comment' => $request->comment,
            'expired_at' => Carbon::now()->addDays(14),
            'shipping_type' => Shipping::TYPE_OFFICE
        ]);

        $ticketsPrice = $this->updateTickets(
            $request->tickets,
            $order->id,
            $clientId,
            Ticket::RESERVED
        )->sum('price');

        $orderDiscount = $request->main_discount;
        $subtotal = $order->ticketsPriceWithDiscount - $orderDiscount;
        $realizatorCommision= $subtotal * ($request->realizator_commission / 100);

       $order->update([
           'realizator_commission' => $realizatorCommision,
           'realizator_percent' => $request->realizator_commission,
           'subtotal' => $ticketsPrice,
           'discount' => $orderDiscount,
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
            'manager_id' => Auth::id(),
            'comment' => $request->comment,
            'expired_at' => Carbon::now()->addDays(14),
            'shipping_type' => $request->shipping_type,
            'discount' => $request->main_discount
        ];

        if ($request->shipping_type == Shipping::TYPE_POST) {
            $address = Address::findOrFail($request->address_id);
            $shippingZones = $this->shippingService->getShippingOptionsForCountry($address->country);

            $data['shipping_zone_id'] = isset($shippingZones[0]) ? $shippingZones[0]['id'] : null;
            $data['shipping_price'] = isset($shippingZones[0]) ? $shippingZones[0]['price'] : 0;
            $data['payment_method_id'] = PaymentMethod::paymentDelay()->first()->id;
        }

        if ($request->shipping_type == Shipping::TYPE_EMAIL) {
            $data['payment_method_id'] = PaymentMethod::paymentDelay()->first()->id;
        }

        $order = Order::create($data);

        if ($request->shipping_type == Shipping::TYPE_POST) {
            $shippingAddressData = $address->only([
                'apartment',
                'first_name',
                'house',
                'last_name',
                'post_code',
                'street'
            ]);

            $shippingAddressData += [
                'city' => $address->city->name ?? '',
                'country' => $address->country->name,
                'order_id' => $order->id
            ];

            ShippingAddress::updateOrCreate($shippingAddressData);
        }

        $ticketsPrice = $this->updateTickets(
            $request->tickets,
            $order->id,
            $clientId,
            Ticket::RESERVED
        )->sum('price');

        $order->update([
            'subtotal' => $ticketsPrice,
        ]);

        return $order;
	}

    /**
     * Confirm order payment
     *
     * @param Order $order
     * @return Order
     */
    public function confirmPayment(Order $order)
    {
        $order->update([
            'payer_id' => Auth::id(),
            'paid_at' => Carbon::now(),
            'payment_status' => Shipping::STATUS_DELIVERED
        ]);

        return response()->json(null, 204);
	}

    /**
     * Change order status and payment method
     *
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOrderStatus(Order $order, Request $request)
    {
        $data = ['status' => $request->status];

        if ($request->status == Order::STATUS_CONFIRMED) {
            $data = [
                'payer_id' => Auth::id(),
                'paid_at' => Carbon::now(),
                'status' => Order::STATUS_CONFIRMED,
                'payment_method_id' => $request->payment_method_id
            ];
        }

        $order->update($data);

        return response()->json(null, 204);
	}

    /**
     * Change shipping status
     *
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeShippingStatus(Order $order, Request $request)
    {
        $order->update([
            'shipping_status' => $request->shipping_status
        ]);

        return response()->json(null, 204);
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
                $country = Country::find($address->country_id);
                $shippingPrice = $this->shippingService->getShippingOptionsForCountry($country);

                return [
                    'id' => $address->id,
                    'address' => $address->full,
                    'shippingPrice' => isset($shippingPrice[0]) ? $shippingPrice[0]['price'] : 0
                ];
            });

        return response()->json($addresses);
	}

    /**
     * Delete ticket from order and recalculate order subtotal
     *
     * @param Order $order
     * @param Ticket $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTicket(Order $order, Ticket $ticket)
    {
        $order->subtotal -= $ticket->price;
        $order->save();

        $this->ticketService->freeTicketFromOrder($ticket);

        return response()->json(null, 204);
	}

    /**
     * Show the form for editing the specified order.
     *
     * @param Order $order
     * @return \Illuminate\View\View
     */
	public function edit(Order $order)
	{
		return view('Admin::order.edit', compact('order'));
	}

    /**
     * Update the specified order in storage.
     *
     * @param Order $order
     * @param UpdateOrderRequest|Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function update(Order $order, UpdateOrderRequest $request)
	{
        $data['subtotal'] = $this->updateTickets(
            $request->tickets,
            $order->id,
            $order->user_id,
            Ticket::SOLD
        )->sum('price');

        $data['comment'] = $request->comment;

        // Change shipping type
        if ($order->shipping_type != $request->shipping_type) {
            $data['shipping_type'] = $request->shipping_type;

            // Change free to paid
            if ($request->shipping_type == Shipping::TYPE_POST) {
                $address = Address::findOrFail($request->address_id);
                $shippingZones = $this->shippingService->getShippingOptionsForCountry($address->country);

                $data['shipping_zone_id'] = isset($shippingZones[0]) ? $shippingZones[0]['id'] : null;
                $data['shipping_price'] = isset($shippingZones[0]) ? $shippingZones[0]['price'] : 0;
                $data['payment_method_id'] = PaymentMethod::paymentDelay()->first()->id;

                $shippingAddressData = $address->only([
                    'apartment',
                    'first_name',
                    'house',
                    'last_name',
                    'post_code',
                    'street'
                ]);

                $shippingAddressData += [
                    'city' => $address->city->name ?? '',
                    'country' => $address->country->name,
                    'order_id' => $order->id
                ];

                ShippingAddress::updateOrCreate($shippingAddressData);
            } else {
                $data['shipping_zone_id'] =  null;
                $data['shipping_price'] =  0;
                $data['courier_price'] =  0;

                $order->shippingAddress()->delete();
            }
        }

        $order->update($data);

        Alert::success(trans('Admin::admin.controller-successfully_created', [
            'item' => trans('Admin::models.OrderController')
        ]))->flash();

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
