<?php
namespace App\Handlers\Native;


use App\Handlers\Abstracts\EventHandlerInterface;
use App\Models\Event;
use App\Models\Ticket;

use Carbon\Carbon;
use Cart;

class NativeEventHandler implements EventHandlerInterface
{
    public function schema(Event $event)
    {
        $event->loadMissing('eventImage');

        $ts = $this->getInitialTimestamp();

        $hall = $this->getHallData($event);
        $zones = $this->getZonesData($event);
        $places = $this->getPlacesData($event);
        $labels = $this->getLabelsData($event);
        $prices = $this->getPricesData($event);
        $priceGroups = $this->getPriceGroupsData($event);

        return compact(
            'event',
            'hall',
            'labels',
            'zones',
            'places',
            'prices',
            'priceGroups',
            'ts'
        );
    }

    protected function getInitialTimestamp()
    {
        $data = \DB::select('select UNIX_TIMESTAMP() as ts');

        return ($data[0]->ts ?? 0);
    }

    protected function getHallData(Event $event)
    {
        $data = \DB::table('halls')
            ->leftJoin('buildings', 'halls.building_id', '=', 'buildings.id')
            ->leftJoin('cities', 'buildings.city_id', '=', 'cities.id')
            ->select([
                'halls.id as id',
                'halls.name as name',
                'buildings.id as building_id',
                'buildings.name as building_name',
                'buildings.address as building_address',
                'cities.id as city_id',
                'cities.name as city_name',
            ])
            ->where('halls.id', $event->hall_id)
            ->first()
        ;

        return $data;
    }

    protected function getZonesData(Event $event)
    {
        $data = \DB::table('zones')
            ->where('hall_id', $event->hall_id)
            ->select(['id', 'name', 'color'])
            ->get()
        ;

        return $data;
    }

    protected function getPlacesData(Event $event)
    {
        $places = \DB::table('places')
            ->where('hall_id', $event->hall_id)
            ->select([
                'id',
                'text',
                'hall_id',
                'zone_id',
                'template',
                'x',
                'y',
                'width',
                'height',
                'path',
                'rotate',
                'row',
                'num',
                \DB::raw('(select count(tickets.id) from tickets where tickets.deleted_at is null and tickets.place_id = places.id and tickets.status = ' . Ticket::RESERVED . ') as reserved'),
                \DB::raw('(select count(tickets.id) from tickets where tickets.deleted_at is null and tickets.place_id = places.id and tickets.status = ' . Ticket::AVAILABLE . ') as available'),
                \DB::raw('(select tickets.price_id from tickets where tickets.deleted_at is null and tickets.place_id = places.id limit 1) as price_id'),
            ])
            ->get()
        ;

        return $places;
    }

    protected function getLabelsData(Event $event)
    {
        $labels = \DB::table('labels')
            ->where('hall_id', $event->hall_id)
            ->select([
                'id', 'x', 'y', 'text', 'is_bold', 'is_italic', 'layer', 'rotation'
            ])
            ->get()
        ;

        return $labels;
    }

    protected function getPricesData(Event $event)
    {
        $prices = \DB::table('prices')
            ->where('event_id', $event->id)
            ->select(['id', 'price', 'color'])
            ->get()
        ;

        return $prices;
    }

    protected function getPriceGroupsData(Event $event)
    {
        $priceGroups = \DB::table('price_groups')
            ->where('event_id', $event->id)
            ->select(['id', 'name','discount'])
            ->get()
        ;

        return $priceGroups;
    }

    public function delta(Event $event, $ts)
    {
        $end = time();
        $start = $ts ?: $end;

        $placeIds = \DB::table('tickets')
            ->distinct()
            ->where('event_id', $event->id)
            ->whereBetween('updated_at', [
                Carbon::createFromTimestamp($start),
                Carbon::createFromTimestamp($end)
            ])
            ->pluck('place_id')
        ;

        $places = \DB::table('places')
            ->whereIn('id', $placeIds)
            ->select([
                'id',
                'text',
                'zone_id',
                'template',
                'x',
                'y',
                'width',
                'height',
                'path',
                'rotate',
                'row',
                'num',
                \DB::raw('(select count(tickets.id) from tickets where tickets.deleted_at is null and tickets.place_id = places.id and tickets.status = ' . Ticket::RESERVED . ') as reserved'),
                \DB::raw('(select count(tickets.id) from tickets where tickets.deleted_at is null and tickets.place_id = places.id and tickets.status = ' . Ticket::AVAILABLE . ') as available'),
                \DB::raw('(select tickets.price_id from tickets where tickets.deleted_at is null and tickets.place_id = places.id) as price_id'),
            ])
            ->get()
        ;

        return [
            'updates' => $places,
            'timestamp' => $end,
        ];
    }

    public function statistic(Event $event)
    {
        $raw = \DB::table('prices')
            ->select([
                'prices.color as color',
                'prices.price as price',
                \DB::raw('count(tickets.id) as available')
            ])
            ->where('prices.event_id', $event->id)
            ->leftJoin('tickets', 'tickets.price_id', '=', 'prices.id')
            ->where('tickets.status', Ticket::AVAILABLE)
            ->orderBy('price', 'asc')
            ->groupBy('color', 'price')
            ->get()
        ;

        $data = json_decode(json_encode($raw), true);

        return $data;
    }
}