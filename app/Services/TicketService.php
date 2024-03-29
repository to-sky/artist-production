<?php

namespace App\Services;


use App\Handlers\Abstracts\TicketHandlerInterface;
use App\Handlers\Kartina\KartinaTicketHandler;
use App\Handlers\Native\NativeTicketHandler;
use App\Helpers\PriceHelper;
use App\Models\Event;
use App\Models\Order;
use App\Models\Place;
use App\Models\Price;
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

        $handler = $this->getHandler($data['event_id']);
        $tickets = $handler->reserve($data);

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
     * Make reservation for provided order
     *
     * @param Order $order
     * @return mixed
     */
    public function reserveByOrder(Order $order)
    {
        $events = $order->events;

        $reservationDate = Carbon::now()->addWeeks(2);
        foreach ($events as $event) {
            if ($reservationDate->greaterThan($event->date)) {
                $reservationDate = $event->date;
            }
        }

        foreach ($order->tickets as $ticket) {
            $this->reserveTicket($ticket, $order->user, [
                'reserved_to' => $reservationDate,
            ]);
        }

        return $order->tickets;
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
        $ticket->reserved_to = Carbon::now()->addMinutes(30);

        foreach ($fill as $k => $v) {
            $ticket->$k = $v;
        }

        $ticket->price = PriceHelper::getPriceWithGroup($ticket->price_id, $ticket->price_group_id);

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
            ->where('reserved_to', '<=', Carbon::now())
            ->get()
        ;

        foreach ($tickets as $ticket) {
            $this->freeTicket($ticket);
        }
    }

    public function freeByOrder(Order $order)
    {
        foreach ($order->tickets as $ticket) {
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
        $handler = $this->getHandler($ticket->event_id);
        $handler->free($ticket);
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
        $ticket->price = PriceHelper::getPriceWithGroup($ticket->price_id, $ticket->price_group_id);
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
            if (empty($eventId) || $eventId == $item->model->event_id) {
                /** @var Ticket $t */
                $t = $item->model;

                $t->discount_price = $t->getBuyablePrice();
                $t->discount_name = $t->priceGroup()->value('name') ?: '';
                $t->place_row = $t->place->row;
                $t->place_num = $t->place->num;

                $tickets->push($t);
            }
        }

        return $tickets;
    }

    /**
     *
     *
     * @param $event_id
     * @return TicketHandlerInterface
     */
    protected function getHandler($event_id)
    {
        $isKartina = Event::whereId($event_id)->value('kartina_id');

        if ($isKartina) {
            return new KartinaTicketHandler();
        } else {
            return new NativeTicketHandler();
        }
    }

    /**
     * Get ticket by barcode value
     *
     * @param $code
     * @return Ticket|null
     */
    public function getByBarcode($code)
    {
        $ticket = Ticket
            ::whereIn('status', [
                Ticket::RESERVED,
                Ticket::SOLD,
            ])
            ->whereBarcode($code)
            ->whereNull('kartina_id')
            ->first()
        ;

        return $ticket;
    }

    public function refundById($ids)
    {
        if (!is_array($ids)) $ids = [$ids];

        $tickets = Ticket::whereIn('id', $ids)->get();

        foreach ($tickets as $ticket) {
            $this->freeTicket($ticket);
        }
    }
}
