<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;

class TicketController extends ApiController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTicket(Request $request)
    {
        $data = $request->all([
            'event_id',
            'place_id',
            'price_id',
        ]);

        $count = $request->get('count') ?: 1;

        if ($count > 1) {
            $tickets = $this->updateFanZone($data, $count);

            return response()->json(compact('tickets'));
        }

        if (is_array($data['place_id'])) {
            $tickets = [];
            foreach ($data['place_id'] as $pid) {
                $ticket = $this->updatePlace([
                    'event_id' => $data['event_id'],
                    'place_id' => $pid,
                    'price_id' => $data['price_id'],
                ]);

                $tickets[] = $ticket;
            }

            return response()->json(compact('tickets'));
        }

        $ticket = $this->updatePlace($data);

        return response()->json(compact('ticket'));
    }
}
