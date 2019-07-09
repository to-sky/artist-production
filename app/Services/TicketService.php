<?php

namespace App\Services;


use App\Models\Event;
use App\Models\Order;
use App\Models\Place;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class TicketService
{
    /**
     * Manage event/place/price relations for tickets
     *
     * @param Request $request
     * @return Ticket[]|Ticket
     * @throws \Exception
     */
    public function manage(Request $request)
    {
        $data = $request->all([
            'event_id',
            'place_id',
            'price_id',
        ]);

        $count = $request->get('count') ?: 1;

        if ($count > 1) {
            $tickets = $this->updateFanZone($data, $count);

            return $tickets;
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

            return $tickets;
        }

        $ticket = $this->updatePlace($data);

        return [$ticket];
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

    /**
     * Add tickets to cart
     *
     * @param array $data
     * @param \App\Models\User|null $user
     * @return mixed
     */
    public function reserve($data, $user = null)
    {
        if (empty($user)) $user = auth()->user();

        $tickets = Ticket
            ::whereStatus(Ticket::AVAILABLE)
            ->whereEventId($data['event_id'])
            ->wherePlaceId($data['place_id'])
            ->limit($data['count'])
            ->get()
        ;

        foreach ($tickets as $ticket) {
            $this->reserveTicket($ticket, $user, $data['fill'] ?? []);
            Cart::add($ticket);
        }

        return $tickets;
    }

    /**
     * Get reservation data from request
     *
     * @param Request $request
     * @return array
     */
    public function getReserveDataFromRequest(Request $request)
    {
        return $request->only([
            'event_id',
            'place_id',
            'count',
            'fill',
        ]);
    }

    /**
     * Ticket reservation
     *
     * @param Ticket $ticket
     * @param \App\Models\User $user
     * @param array $fill
     */
    public function reserveTicket(Ticket $ticket, $user, $fill = [])
    {
        $ticket->user()->associate($user);
        $ticket->status = Ticket::RESERVED;

        foreach ($fill as $k => $v) {
            $ticket->$k = $v;
        }

        $ticket->save();
    }

    /**
     * Attach tickets from cart to $order
     *
     * @param Order $order
     * @return array
     */
    public function attachCartToOrder(Order $order)
    {
        $attachedTickets = [];
        foreach (Cart::content() as $id => $reserved) {
            $ticket = $reserved->model;

            $this->attachTicketToOrder($order, $ticket);
            Cart::remove($id);
            $attachedTickets[] = $ticket;
        }

        return $attachedTickets;
    }

    /**
     * Attach $ticket to $order
     *
     * @param Order $order
     * @param Ticket $ticket
     */
    public function attachTicketToOrder(Order $order, Ticket $ticket)
    {
        $ticket->order()->associate($order);
        $ticket->save();
    }

    /**
     * Remove tickets prom cart
     *
     * @param array $data
     * @return array
     */
    public function free($data)
    {
        $filter = $data['filter'];
        $filter['place_id'] = $data['place_id'];
        $filter['event_id'] = $data['event_id'];
        $freedTickets = [];
        foreach (Cart::content() as $id => $reserved) {
            $ticket = $reserved->model;

            if ($this->_passingFilter($ticket, $filter)) {
                Cart::remove($id);
                $this->freeTicket($ticket);
                $freedTickets[] = $ticket;
            }
        }

        return $freedTickets;
    }

    /**
     * get data to free tickets from request
     *
     * @param Request $request
     * @return array
     */
    public function getFreeDataFromRequest(Request $request)
    {
        $data = $request->only([
            'filter',
            'place_id',
            'event_id',
        ]);

        if (empty($data['filter'])) $data['filter'] = [];

        return $data;
    }

    /**
     * Free ticket by ticket id
     *
     * @param int $ticketId
     * @return array
     */
    public function freeById($ticketId)
    {
        $tickets = [];
        foreach (Cart::content() as $id => $reserved) {
            if ($reserved->model->id == $ticketId) {
                $ticket = $reserved->model;
                Cart::remove($id);
                $this->freeTicket($ticket);
                $tickets[] = $ticket;
            }
        }

        return $tickets;
    }

    /**
     * Checks if ticket attributes equals filters
     *
     * @param Ticket $ticket
     * @param array $filters
     * @return bool
     */
    protected function _passingFilter(Ticket $ticket, $filters = [])
    {
        foreach ($filters as $k => $v) {
            if ($ticket->$k != $v) return false;
        }

        return true;
    }

    /**
     * Free tickets which was reserved more than 30 min ago
     */
    public function freeByTimeout()
    {
        $tickets = Ticket
            ::whereStatus(Ticket::RESERVED)
            ->where('updated_at', '<=', Carbon::now()->addMinutes(-30))
            ->get()
        ;

        foreach ($tickets as $ticket) {
            $this->freeTicket($ticket);
        }
    }

    /**
     * Free ticket reservation
     *
     * @param Ticket $ticket
     */
    protected function freeTicket(Ticket $ticket)
    {
        $ticket->user()->dissociate();
        $ticket->status = Ticket::AVAILABLE;
        $ticket->reserved_to = null;
        $ticket->save();
    }

    public function sold(Order $order)
    {
        foreach ($order->tickets as $ticket) {
            $this->soldTicket($ticket);
        }
    }

    public function soldTicket(Ticket $ticket)
    {
        $ticket->reserved_to = null;
        $ticket->status = Ticket::SOLD;
        $ticket->save();
    }

    public function freeTicketFromOrder(Ticket $ticket)
    {
        $ticket->price = null;
        $ticket->status = Ticket::AVAILABLE;
        $ticket->discount = 0;
        $ticket->reserved_to = null;
        $ticket->order()->dissociate();
        $ticket->priceGroup()->dissociate();
        $ticket->user()->dissociate();
        $ticket->save();
    }

    /**
     * Empty cart. Only for event with eventId if set.
     *
     * @param int|null $eventId
     */
    public function emptyCart($eventId = null)
    {
        foreach (Cart::content() as $id => $reserved) {
            Cart::remove($id);

            if (empty($eventId) || $eventId == $reserved->model->event_id)
                $this->freeTicket($reserved->model);
        }
    }

    /**
     * Get delta of changed tickets for event between start & end
     *
     * @param Event $event
     * @param int $start
     * @param null|int $end
     * @return Ticket[]
     */
    public function getStatusDelta(Event $event, $start, $end = null)
    {
        if (empty($end)) $end = time();

        $reservedIds = [];
        foreach (Cart::content() as $item) {
            $reservedIds[] = $item->model->id;
        }

        $tickets = Ticket
            ::withTrashed()
            ->whereEventId($event->id)
            ->whereNotIn('id', $reservedIds)
            ->whereBetween('updated_at', [
                Carbon::createFromTimestamp($start),
                Carbon::createFromTimestamp($end)
            ])
            ->get()
        ;

        return $tickets;
    }

    /**
     * Get tickets currently into cart. Only for event with eventId if set.
     *
     * @param int|null $eventId
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCartTickets($eventId = null)
    {
        $tickets = collect();
        foreach (Cart::content() as $id => $item) {
            if (empty($eventId) || $eventId == $item->model->event_id)
                $tickets->push($item->model);
        }

        return $tickets;
    }
}
