<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\AdminController;
use Redirect;
use Schema;
use App\Models\Event;
use App\Modules\Admin\Requests\CreateEventRequest;
use App\Modules\Admin\Requests\UpdateEventRequest;
use Illuminate\Http\Request;

use App\Models\Building;

use Prologue\Alerts\Facades\Alert;

class EventController extends AdminController {

	/**
	 * Display a listing of events
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $events = Event::with("buildings")->get();

		return view('Admin::event.index', compact('events'));
	}

	/**
	 * Show the form for creating a new event
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $buildings = Building::orderBy('name')->pluck("name", "id")->prepend('Please select', 0);


	    return view('Admin::event.create', compact("buildings"));
	}

	/**
	 * Store a newly created event in storage.
	 *
     * @param CreateEventRequest|Request $request
	 */
	public function store(CreateEventRequest $request)
	{
        if (is_null($request->is_active)) {
            $request->merge(['is_active' => 0]);
        }

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
	    $buildings = Building::orderBy('name')->pluck("name", "id")->prepend('Please select', 0);


		return view('Admin::event.edit', compact('event', "buildings"));
	}

	/**
	 * Update the specified event in storage.
     * @param UpdateEventRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateEventRequest $request)
	{
		$event = Event::findOrFail($id);

		if (is_null($request->is_active)) {
            $request->merge(['is_active' => 0]);
        }

		$event->update($request->all());

        Alert::success(trans('Admin::admin.users-controller-successfully_created'))->flash();

        $this->redirectService->setRedirect($request);
        return $this->redirectService->redirect($request);
	}

	/**
	 * Remove the specified event from storage.
	 *
	 * @param  int  $id
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
