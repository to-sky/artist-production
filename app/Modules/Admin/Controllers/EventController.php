<?php

namespace App\Modules\Admin\Controllers;

use App\Models\{Building, City, Hall, Event, Price, PriceGroup};
use App\Modules\Admin\Requests\EventRequest;
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
		return view('Admin::event.index', [
		    'events' => Event::all()
        ]);
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
     * @param EventRequest|Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(EventRequest $request)
	{
		$event = Event::create($request->all());

        $request->merge(['id' => $event->id]);

		Alert::success(trans('Admin::admin.controller-successfully_created', ['item' => trans('Admin::models.Event')]))->flash();

        $this->redirectService->setRedirect($request);

        return $this->redirectService->redirect($request);
	}

    /**
     * Show the form for editing the specified event.
     *
     * @param Event $event
     * @return \Illuminate\View\View
     */
	public function edit(Event $event)
    {
        $this->generateParams();

		return view('Admin::event.edit', compact('event'));
	}

    /**
     * Update the specified event in storage.
     * @param Event $event
     * @param EventRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
	public function update(Event $event, EventRequest $request)
	{
		$event->update($request->all());

		if ($request->prices) {
            $this->createOrUpdatePrices($event, 'prices');
        }

		if ($request->priceGroups) {
            $this->createOrUpdatePrices($event, 'priceGroups');
        }

        Alert::success(trans('Admin::admin.controller-successfully_updated', ['item' => trans('Admin::models.Event')]))->flash();

        $this->redirectService->setRedirect($request);

        return $this->redirectService->redirect($request);
	}

    /**
     * Create or update prices/price groups
     *
     * @param Event $event
     * @param $relation
     * @return \Illuminate\Support\Collection
     */
    public function createOrUpdatePrices(Event $event, $relation)
    {
        return collect(request()->$relation)->map(function ($item) use ($event, $relation) {
            if (! isset($item['id'])) {
                return $event->$relation()->create($item);
            }

            $model = $event->$relation()->find($item['id']);
            $model->update($item);

            return $model;
        });
    }

    /**
     * Remove the specified event from storage.
     *
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
	public function destroy(Event $event)
	{
	    $event->delete();

		return response()->json(null, 204);
	}

    /**
     * Remove price
     *
     * @param Price $price
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deletePrice(Price $price)
    {
        $price->delete();

        return response()->json(null, 204);
    }

    /**
     * Remove price group
     *
     * @param PriceGroup $priceGroup
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deletePriceGroup(PriceGroup $priceGroup)
    {
        $priceGroup->delete();

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
     * Display widget to assign prices to places of event
     *
     * @param Event $event
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function hallPlaces(Event $event)
    {
        return view('Admin::event.hallPlaces', compact('event'));
    }

    /**
     * Share the same variables for different views
     */
    public function generateParams()
    {
        $cities = City::has('buildings.halls.places')
            ->pluck('name', 'id')
            ->prepend(__('Admin::admin.select-item', ['item' => mb_strtolower(__('Admin::models.City'))]), '');

        $buildings = Building::has('halls.places')
            ->pluck('name', 'id')
            ->prepend(__('Admin::admin.select-item', ['item' => mb_strtolower(__('Admin::models.Building'))]), '');

        $halls = Hall::has('places')
            ->pluck('name', 'id')
            ->prepend(__('Admin::admin.select-item', ['item' => mb_strtolower(__('Admin::models.Hall'))]), '');

        view()->share('cities', $cities);
        view()->share('buildings', $buildings);
        view()->share('halls', $halls);
    }
}
