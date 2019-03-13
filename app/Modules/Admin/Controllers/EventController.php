<?php

namespace App\Modules\Admin\Controllers;

use App\Models\{Building, City, Hall, Event};
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
        $this->generateParams();

	    return view('Admin::event.create');
	}

    /**
     * Get related buildings for city
     *
     * @return mixed
     */
    public function getBuildings()
    {
        return Building::where('city_id', request()->city_id)
            ->has('halls')
            ->get(['id', 'name'])
            ->map(function ($building) {
                return [
                    'id' => $building->id,
                    'text' => $building->name,
                ];
            });
	}

    /**
     * Get related halls for building
     *
     * @return mixed
     */
    public function getHalls()
    {
        return Hall::where('building_id', request()->building_id)
            ->has('places')
            ->get(['id', 'name'])
            ->map(function ($hall) {
                return [
                    'id' => $hall->id,
                    'text' => $hall->name,
                ];
            });
	}

    /**
     * Store a newly created event in storage.
     *
     * @param CreateEventRequest|Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(CreateEventRequest $request)
	{
        $request['is_active'] = $request->is_active ? 1 : 0;

		Event::create($request->all());

        Alert::success(trans('Admin::admin.controller-successfully_created', ['item' => trans('Admin::models.Event')]))->flash();

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

		$this->generateParams();

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

        $request['is_active'] = $request->is_active ? 1 : 0;

		$event->update($request->all());

        Alert::success(trans('Admin::admin.controller-successfully_updated', ['item' => trans('Admin::models.Event')]))->flash();

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


    /**
     * Share the same variables for different views
     */
    public function generateParams()
    {
        $cities = City::has('buildings.halls.places')
            ->pluck('name', 'id')
            ->prepend(__('Admin::admin.select-item', ['item' => __('city')]), '');

        $buildings = Building::has('halls.places')
            ->pluck('name', 'id')
            ->prepend(__('Admin::admin.select-item', ['item' => __('building')]), '');

        $halls = Hall::has('places')
            ->pluck('name', 'id')
            ->prepend(__('Admin::admin.select-item', ['item' => __('hall')]), '');

        view()->share('cities', $cities);
        view()->share('buildings', $buildings);
        view()->share('halls', $halls);
    }
}
