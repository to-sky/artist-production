<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\AdminController;
use Redirect;
use Schema;
use App\Models\City;
use App\Modules\Admin\Requests\CreateCityRequest;
use App\Modules\Admin\Requests\UpdateCityRequest;
use Illuminate\Http\Request;

use App\Models\Country;

use Prologue\Alerts\Facades\Alert;

class CityController extends AdminController {

	/**
	 * Display a listing of cities
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $cities = City::with("countries")->get();

		return view('Admin::city.index', compact('cities'));
	}

	/**
	 * Show the form for creating a new city
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $countries = Country::pluck("name", "id")->prepend('Please select', 0);


	    return view('Admin::city.create', compact("countries"));
	}

	/**
	 * Store a newly created city in storage.
	 *
     * @param CreateCityRequest|Request $request
	 */
	public function store(CreateCityRequest $request)
	{

		City::create($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Show the form for editing the specified city.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$city = City::find($id);
	    $countries = Country::orderby('name')->pluck("name", "id")->prepend('Please select', 0);


		return view('Admin::city.edit', compact('city', "countries"));
	}

	/**
	 * Update the specified city in storage.
     * @param UpdateCityRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateCityRequest $request)
	{
		$city = City::findOrFail($id);



		$city->update($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Remove the specified city from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		City::destroy($id);

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
            City::destroy($toDelete);
        } else {
            City::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.cities.index');
    }

}
