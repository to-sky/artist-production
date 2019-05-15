<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\AdminController;
use Redirect;
use Schema;
use App\Models\Order;
use App\Modules\Admin\Requests\CreateOrderRequest;
use App\Modules\Admin\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;


use Prologue\Alerts\Facades\Alert;

class OrderController extends AdminController {

	/**
	 * Display a listing of orders
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $orders = Order::all();

		return view('Admin::order.index', compact('orders'));
	}

	/**
	 * Show the form for creating a new order
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    return view('Admin::order.create');
	}

	/**
	 * Store a newly created order in storage.
	 *
     * @param CreateOrderRequest|Request $request
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(CreateOrderRequest $request)
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
