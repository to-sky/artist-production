<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Building;
use App\Models\City;
use App\Models\Hall;
use App\Models\Event;
use App\Modules\Admin\Requests\CreateEventRequest;
use App\Modules\Admin\Requests\UpdateEventRequest;
use Illuminate\Http\Request;

use Prologue\Alerts\Facades\Alert;

class EventController extends AdminController {

	/**
	 * Display a listing of events
	 *
     * @return \Illuminate\View\View
	 */
	public function index()
    {
        $events = Event::all();

		return view('Admin::event.index', compact('events'));
	}

	/**
	 * Show the form for creating a new event
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
        $cities = City::has('buildings.halls.places')->pluck("name", "id")->prepend('Please select', '');
        $buildings = Building::has('halls')->pluck("name", "id")->prepend('Please select', '');
        $halls = Hall::has('places')->pluck("name", "id")->prepend('Please select', '');

	    return view('Admin::event.create', compact('buildings', 'cities','halls'));
	}

    public function getBuildings()
    {
        return  Building::where('city_id', request()->city_id)->has('halls')->pluck('name', 'id')->toJson();
	}

    /**
     * Store a newly created event in storage.
     *
     * @param CreateEventRequest|Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(CreateEventRequest $request)
	{
		Event::create($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Show the form for editing the specified event.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$event = Event::find($id);

		return view('Admin::event.edit', compact('event'));
	}

    /**
     * Update the specified event in storage.
     * @param UpdateEventRequest|Request $request
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
	public function update($id, UpdateEventRequest $request)
	{
		$event = Event::findOrFail($id);

		$event->update($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

    /**
     * Remove the specified event from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
	public function destroy($id)
	{
		Event::destroy($id);

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
            Event::destroy($toDelete);
        } else {
            Event::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.events.index');
    }

}
