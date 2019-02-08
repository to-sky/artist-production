<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\AdminController;
use Redirect;
use Schema;
use App\Models\Price;
use App\Modules\Admin\Requests\CreatePriceRequest;
use App\Modules\Admin\Requests\UpdatePriceRequest;
use Illuminate\Http\Request;


use Prologue\Alerts\Facades\Alert;

class PriceController extends AdminController {

	/**
	 * Display a listing of prices
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $prices = Price::all();

		return view('Admin::price.index', compact('prices'));
	}

	/**
	 * Show the form for creating a new price
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('Admin::price.create');
	}

	/**
	 * Store a newly created price in storage.
	 *
     * @param CreatePriceRequest|Request $request
	 */
	public function store(CreatePriceRequest $request)
	{
	    
		Price::create($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Show the form for editing the specified price.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$price = Price::find($id);
	    
	    
		return view('Admin::price.edit', compact('price'));
	}

	/**
	 * Update the specified price in storage.
     * @param UpdatePriceRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdatePriceRequest $request)
	{
		$price = Price::findOrFail($id);

        

		$price->update($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Remove the specified price from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Price::destroy($id);

		return response()->json(null, 204);
	}

    /**
     * Mass delete function from index page
     * @param Request $request
     *
     * @return mixed
     */
    public function massDelete(Request $request)
    {
        if ($request->get('toDelete') != 'mass') {
            $toDelete = json_decode($request->get('toDelete'));
            Price::destroy($toDelete);
        } else {
            Price::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.prices.index');
    }

}
