<?php

namespace App\Modules\Admin\Controllers;

use App\Models\{Order, Event, Ticket, User};
use App\Modules\Admin\Controllers\AdminController;
use App\Services\TicketService;
use App\Modules\Admin\Requests\CreateOrderRequest;
use App\Modules\Admin\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;


use Prologue\Alerts\Facades\Alert;

class OrderController extends AdminController {

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
     * @param TicketService $ticketService
     * @return \Illuminate\View\View
     */
	public function create(TicketService $ticketService)
	{
	    return view('Admin::order.create', [
	        'events' => Event::all(),
            'users' => User::all(),
            'ticketsData' => $this->getTicketData($ticketService)
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
     * @param TicketService $ticketService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateTicketsTable(TicketService $ticketService)
    {
        $ticketsData = $this->getTicketData($ticketService);

        return view('Admin::order.partials._tickets_table', compact('ticketsData'));
	}

    /**
     * Generate tickets data
     *
     * @param TicketService $ticketService
     * @return array
     */
    public function getTicketData(TicketService $ticketService)
    {
        $ticketsData = [];
        $ticketService->getCartTickets()->map(function ($ticket) use (&$ticketsData) {
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
		Order::create($request->all());

        Alert::success(trans('Admin::admin.controller-successfully_created', ['item' => trans('Admin::models.OrderController')]))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
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
	 * @param $id
     * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($id)
	{
		Order::destroy($id);

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
