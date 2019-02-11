<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\AdminController;
use Redirect;
use Schema;
use App\Models\Building;
use App\Modules\Admin\Requests\CreateBuildingRequest;
use App\Modules\Admin\Requests\UpdateBuildingRequest;
use Illuminate\Http\Request;

use App\Models\City;

use Prologue\Alerts\Facades\Alert;

class BuildingController extends AdminController {

	/**
	 * Display a listing of buildings
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $buildings = Building::with("cities")->get();

		return view('Admin::building.index', compact('buildings'));
	}

	/**
	 * Show the form for creating a new building
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $cities = City::orderby('name')->pluck("name", "id")->prepend('Please select', 0);


	    return view('Admin::building.create', compact("cities"));
	}

	/**
	 * Store a newly created building in storage.
	 *
     * @param CreateBuildingRequest|Request $request
	 */
	public function store(CreateBuildingRequest $request)
	{

		Building::create($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Show the form for editing the specified building.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$building = Building::find($id);
	    $cities = City::orderBy('name')->pluck("name", "id")->prepend('Please select', 0);


		return view('Admin::building.edit', compact('building', "cities"));
	}

	/**
	 * Update the specified building in storage.
     * @param UpdateBuildingRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateBuildingRequest $request)
	{
		$building = Building::findOrFail($id);



		$building->update($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Remove the specified building from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Building::destroy($id);

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
            Building::destroy($toDelete);
        } else {
            Building::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.buildings.index');
    }

}
