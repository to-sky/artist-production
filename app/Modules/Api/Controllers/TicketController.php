<?php

namespace App\Modules\Api\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Keygen\Keygen;

class TicketController extends ApiController
{
    /**
     * Updates ticket - price binding for place tickets
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function updateTicket(Request $request)
    {
        $data = $request->all([
            'event_id',
            'place_id',
            'price_id',
        ]);

        $barcode = Keygen::numeric(12)->generate();

        if (Ticket::where('barcode', $barcode)->first()){
            $this->updateTicket($request);
        }

        $data += compact('barcode');

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

    /**
     * Update price binding for sitting place
     *
     * @param $data
     * @return mixed
     */
    protected function updatePlace($data)
    {
        $ticket = Ticket::updateOrCreate(
            [
                'event_id' => $data['event_id'],
                'place_id' => $data['place_id'],
            ],
            $data
        );

        return $ticket;
    }

    /**
     * Update price binding for standing place
     *
     * @param $data
     * @param $count
     * @return array
     *
     * @throws \Exception
     */
    protected function updateFanZone($data, $count)
    {
        /** @var Ticket[] $tickets */
        $tickets = Ticket
            ::withTrashed()
            ->whereEventId($data['event_id'])
            ->wherePlaceId($data['place_id'])
            ->get()
        ;

        $ceil = max($tickets->count(), $count);

        $result = [];
        for ($i = 0; $i < $ceil; $i++) {
            if (isset($tickets[$i])) {
                $ticket = $tickets[$i];
            } else {
                $ticket = new Ticket();
            }

            if ($i > $count - 1) {
                $ticket->delete();
            } else {
                if ($ticket->trashed()) {
                    $ticket->restore();
                }

                $ticket->fill($data);
                $ticket->save();

                $result[] = $ticket;
            }
        }

        return $result;
    }
}
