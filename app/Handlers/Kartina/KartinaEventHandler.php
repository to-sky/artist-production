<?php
namespace App\Handlers\Kartina;

use App\Handlers\Abstracts\EventHandlerInterface;
use App\Libs\Kartina\Purchase;
use App\Models\Event;

class KartinaEventHandler implements EventHandlerInterface
{
    protected $api;

    public function __construct()
    {
        $this->api = new Purchase();
    }

    public function schema(Event $event)
    {
        $event->loadMissing('eventImage');

        $statistics = $this->getHallStatistic($event);
        $ts = $statistics['ts'];

        $hall = $this->getHallData($event);
        $zones = $this->getZonesData($event);
        $places = $this->getPlacesData($event, $statistics['places']);
        $labels = $this->getLabelsData($event);
        $prices = $this->getPricesData($event);
        $priceGroups = [];

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

    protected function getPlacesData(Event $event, $statistics)
    {
        $places = \DB::table('places')
            ->where('hall_id', $event->hall_id)
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
                'kartina_id',
                'price_id',
            ])
            ->get()
        ;

        foreach ($places as &$place) {
            $place->available = $statistics[$place->kartina_id]['free'] ?? 0;
            $place->reserved = $statistics[$place->kartina_id]['reserved'] ?? 0;
        }

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

    protected function getHallStatistic(Event $event)
    {
        $statistics = $this->api->getHallUpdate($event->kartina_id);

        return $statistics;
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

    public function delta(Event $event, $ts)
    {
        $data = $this->api->getHallUpdate($event->kartina_id, $ts);

        if (empty($data['places'])) {
            return [
                'updates' => [],
                'ts' => $ts,
            ];
        }

        $places = \DB::table('places')
            ->whereIn('kartina_id', array_keys($data['places']))
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
                'price_id',
                'kartina_id'
            ])
            ->get()
        ;

        foreach ($places as &$place) {
            $place->available = $data['places'][$place->kartina_id]['free'] ?? 0;
            $place->reserved = $data['places'][$place->kartina_id]['reserved'] ?? 0;
        }

        return [
            'updates' => $places,
            'ts' => $data['ts'],
        ];
    }
}