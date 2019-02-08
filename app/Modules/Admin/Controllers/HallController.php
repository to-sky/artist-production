<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\AdminController;
use Redirect;
use Schema;
use App\Models\Hall;
use App\Modules\Admin\Requests\CreateHallRequest;
use App\Modules\Admin\Requests\UpdateHallRequest;
use Illuminate\Http\Request;

use App\Models\Building;

use Prologue\Alerts\Facades\Alert;

class HallController extends AdminController {

	/**
	 * Display a listing of halls
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $halls = Hall::with("buildings")->get();

		return view('Admin::hall.index', compact('halls'));
	}

	/**
	 * Show the form for creating a new hall
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $buildings = Building::pluck("name", "id")->prepend('Please select', 0);


	    return view('Admin::hall.create', compact("buildings"));
	}

	/**
	 * Store a newly created hall in storage.
	 *
     * @param CreateHallRequest|Request $request
	 */
	public function store(CreateHallRequest $request)
	{

		Hall::create($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Show the form for editing the specified hall.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$hall = Hall::find($id);
	    $buildings = Building::orderBy('name')->pluck("name", "id")->prepend('Please select', 0);


		return view('Admin::hall.edit', compact('hall', "buildings"));
	}

	/**
	 * Update the specified hall in storage.
     * @param UpdateHallRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateHallRequest $request)
	{
		$hall = Hall::findOrFail($id);



		$hall->update($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Remove the specified hall from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Hall::destroy($id);

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
            Hall::destroy($toDelete);
        } else {
            Hall::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.halls.index');
    }

}
