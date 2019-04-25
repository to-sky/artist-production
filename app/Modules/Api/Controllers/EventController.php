<?php

namespace App\Modules\Api\Controllers;

use App\Models\Event;

class EventController extends ApiController
{
    /**
     * Get event data
     *
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Event $event)
    {
        $hall = $event->hall()->get(['id', 'name']);

        $labels = $event->hall->labels()->get([
            'id', 'hall_id', 'x', 'y', 'text', 'is_bold', 'is_italic', 'layer', 'rotation'
        ]);

        $zones = $event->hall->zones()->get(['id', 'hall_id', 'name', 'color']);

        $places = $event->hall->places()->with(['tickets:place_id,id,status'])->get([
            'id', 'hall_id', 'text', 'template', 'x', 'y', 'width', 'height', 'path', 'rotate'
        ]);

        $prices = $event->prices()->get(['id', 'price', 'color']);

        $priceGroups = $event->priceGroups()->get(['id', 'name','discount']);

        return response()->json(compact('hall', 'labels', 'zones', 'places', 'prices', 'priceGroups'), 200);
    }
}
