<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\AdminController;
use Redirect;
use Schema;
use App\Models\Country;
use App\Modules\Admin\Requests\CreateCountryRequest;
use App\Modules\Admin\Requests\UpdateCountryRequest;
use Illuminate\Http\Request;


use Prologue\Alerts\Facades\Alert;

class CountryController extends AdminController {

	/**
	 * Display a listing of countries
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $countries = Country::all();

		return view('Admin::country.index', compact('countries'));
	}

	/**
	 * Show the form for creating a new country
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    return view('Admin::country.create');
	}

	/**
	 * Store a newly created country in storage.
	 *
     * @param CreateCountryRequest|Request $request
	 */
	public function store(CreateCountryRequest $request)
	{

		Country::create($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Show the form for editing the specified country.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$country = Country::find($id);


		return view('Admin::country.edit', compact('country'));
	}

	/**
	 * Update the specified country in storage.
     * @param UpdateCountryRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateCountryRequest $request)
	{
		$country = Country::findOrFail($id);



		$country->update($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Remove the specified country from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Country::destroy($id);

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
            Country::destroy($toDelete);
        } else {
            Country::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.countries.index');
    }

}
