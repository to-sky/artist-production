<?php

namespace App\Modules\Admin\Controllers;

use App\Helpers\FileHelper;
use App\Models\{
    Building, City, Hall, Event, HallBlueprint, Price, PriceGroup
};
use App\Modules\Admin\Requests\EventRequest;
use App\Modules\Admin\Services\RedirectService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

class EventController extends AdminController
{
    protected $uploadService;

    public function __construct(RedirectService $redirectService, UploadService $uploadService)
    {
        $this->uploadService = $uploadService;

        parent::__construct($redirectService);
    }

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
            ->has('hallBlueprints')
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
        return HallBlueprint::where('building_id', request()->building_id)
            ->has('placeBlueprints')
            ->get(['id', 'name', 'revision'])
            ->map(function ($hall) {
                return [
                    'id' => $hall->id,
                    'text' => "$hall->name (v. $hall->revision)",
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
	    $hall = Event::buildHallFromBlueprint($request->get('hall_blueprint_id'));
		$event = Event::create($request->all() + ['hall_id' => $hall->id]);

        $eventImage = $this->uploadService->upload($request, $event, null, 'event_image');
        $freePassLogo = $this->uploadService->upload($request, $event, null, 'free_pass_logo');

        $event->eventImage()->associate(array_shift($eventImage));
        $event->freePassLogo()->associate(array_shift($freePassLogo));
        $event->save();

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
        if ($request->file('event_image')) {
            $this->updateImage($event, 'eventImage', 'event_image');
        }

        if ($request->file('free_pass_logo')) {
            $this->updateImage($event, 'freePassLogo', 'free_pass_logo');
        }

        $event->buildHallFromBlueprint($request->get('hall_blueprint_id'));
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
     * Remove event image
     *
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    function deleteEventImage(Event $event)
    {
        $this->deleteImage($event, 'eventImage');

        return response()->json(null, 204);
    }

    /**
     * Remove free pass logo
     *
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    function deleteFreePassLogo(Event $event)
    {
        $this->deleteImage($event, 'freePassLogo');

        return response()->json(null, 204);
    }

    /**
     * Delete old image and upload new image
     *
     * @param Event $event
     * @param $relation
     * @param $field
     * @return Event
     */
    public function updateImage(Event $event, $relation, $field)
    {
        if ($image = $event->$relation) {
            $this->deleteImage($event, $relation);
        }

        $uploadedImage = $this->uploadService->upload(request(), $event, null, $field);

        $event->$relation()->associate(array_shift($uploadedImage));

        return $event;
    }

    /**
     * Delete image
     *
     * @param Event $event
     * @param $relation
     * @return bool|null
     */
    public function deleteImage(Event $event, $relation)
    {
        FileHelper::delete($event->$relation);
        FileHelper::deleteThumb($event->$relation);

        return $event->$relation->delete();
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
        $cities = City::has('buildings')
            ->pluck('name', 'id')
            ->prepend(__('Admin::admin.select-item', ['item' => mb_strtolower(__('Admin::models.City'))]), '');

        $buildings = Building::has('hallBlueprints')
            ->pluck('name', 'id')
            ->prepend(__('Admin::admin.select-item', ['item' => mb_strtolower(__('Admin::models.Building'))]), '');

        $halls = HallBlueprint::has('placeBlueprints')
            ->pluck('name', 'id')
            ->prepend(__('Admin::admin.select-item', ['item' => mb_strtolower(__('Admin::models.Hall'))]), '');

        view()->share('cities', $cities);
        view()->share('buildings', $buildings);
        view()->share('halls', $halls);
    }
}
