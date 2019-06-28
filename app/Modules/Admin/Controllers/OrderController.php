<?php

namespace App\Modules\Admin\Controllers;

use App\Models\{Order, Event, Ticket, User};
use App\Modules\Admin\Controllers\AdminController;
use App\Services\TicketService;
use Redirect;
use Schema;
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
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    return view('Admin::order.create', [
	        'events' => Event::all(),
            'clients' => User::all(),
            'ticketsData' => Ticket::whereUserId(auth()->id())->with('event')->get()->groupBy('event.name')
        ]);
	}

    public function getSelectedTickets(TicketService $ticketService, Request $request)
    {
        $tickets = $ticketService->getCartTickets($request->event_id)
            ->transform(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'row' => $ticket->place->row,
                    'place' => $ticket->place->num,
                    'price' => $ticket->getBuyablePrice()
                ];
        })->sortBy('place');

        return response()->json($tickets);
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
