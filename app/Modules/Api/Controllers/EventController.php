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
        $hall = $event->hall()->get(['id', 'name'])->toArray();

        $labels = $event->hall->labels()->get([
            'id', 'hall_id', 'x', 'y', 'is_bold', 'is_italic', 'layer', 'rotation'
        ])->toArray();

        $zones = $event->hall->zones()->get(['id', 'hall_id', 'name', 'color'])->toArray();

        $places = $event->hall->places()->get([
            'id', 'hall_id', 'text', 'template', 'x', 'y', 'width', 'height', 'path', 'rotate'
        ])->toArray();

        $prices = $event->prices()->get(['id', 'price', 'color'])->toArray();

        $priceGroups = $event->priceGroups()->get(['id', 'name','discount'])->toArray();

        return response()->json(compact('hall', 'labels', 'zones', 'places', 'prices', 'priceGroups'), 200);
    }
}
