<?php

namespace App\Libs\Kartina;

use App\Models\Building;
use App\Models\City;
use App\Models\Country;
use App\Models\Event;
use App\Models\Hall;
use App\Models\HallBlueprint;
use App\Models\Label;
use App\Models\LabelBlueprint;
use App\Models\ParseEvent;
use App\Models\Place;
use App\Models\PlaceBlueprint;
use App\Models\Zone;
use App\Models\ZoneBlueprint;
use App\Services\UploadService;
use Cache;
use Carbon\Carbon;
use function foo\func;
use Log;

class Api extends Base
{
    /**
     * Auth token
     *
     * @var void
     */
    protected $auth;

    protected $host = 'https://kassir.kartina.tv';

    protected $uploadService;

    /**
     * API urls
     *
     * @var array
     */
    protected $urls = [
        'getAuth' => '/LoginCommand.cmd',
        'getCities' => '/GetCities.cmd',
        'getBuildings' => '/GetBuildings.cmd',
        'getHalls' => '/GetHalls.cmd',
        'getHallSchema' => '/GetFlashHallDataCommand.cmd',
        'getEvents' => '/GetPartnerEventsCommand.cmd',
    ];

    /**
     * Api constructor.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth = $this->getAuth();
        $this->uploadService = new UploadService();
    }

    /**
     * Get auth token
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAuth()
    {
        $auth = $this->sendRequest(
            $this->host.$this->urls[__FUNCTION__],
            ['__uid' => config('kartina.kartina_uid')]
        );

        if (! isset($auth['__auth'])) {
            return null;
        }

        return $auth['__auth'];
    }

    /**
     * Send request with auth params
     *
     * @param $url
     * @param array $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendAuthRequest($url, $params = [])
    {
        return $this->sendRequest($url, ['__auth' => $this->auth] + $params);
    }

    /**
     * Checks if request not empty and not errors
     *
     * @param $request
     * @param bool $full
     * @param string $key
     * @return \Illuminate\Support\Collection|null
     */
    public function filterRequest($request, $full = false, $key = 'list')
    {
        if ($full) {
            return collect($request);
        }

        if (! isset($request[$key])) {
            return null;
        }

        return collect($request[$key]);
    }

    /**
     * Get cities
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCities()
    {
        return $this->filterRequest(
            $this->sendAuthRequest($this->host.$this->urls[__FUNCTION__])
        );
    }

    /**
     * Get buildings
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBuildings()
    {
        return $this->filterRequest(
            $this->sendAuthRequest($this->host.$this->urls[__FUNCTION__])
        );
    }

    /**
     * Get halls
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHalls()
    {
        return $this->filterRequest(
            $this->sendAuthRequest($this->host.$this->urls[__FUNCTION__])
        );
    }

    public function getEvents($id = null)
    {
        $params = [];
        if ($id) $params['EventId'] = $id;

        $events = $this->filterRequest(
            $this->sendAuthRequest(
                $this->host . $this->urls[__FUNCTION__],
                $params
            ),
            false,
            'Events'
        );

        if ($events->count() == 1) {
            return $events->first();
        }

        return $events;
    }

    /**
     * Get hall schema (zones, places)
     *
     * @param $kartinaEventId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHallSchema($kartinaEventId)
    {
        $schema = $this->sendAuthRequest($this->host.$this->urls[__FUNCTION__], ['event' => $kartinaEventId]);

        return $this->filterRequest($schema, true);
    }

    /**
     * Store hall schema (hall, places, zones)
     *
     * @param $schema
     * @param $building
     * @return Hall
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeHallSchema($schema, $building)
    {
        $hall = $this->storeHall($schema, $building);
        $zoneBindings = $this->storeZones($schema['zones'], $hall);
        $this->storePlaces($schema['places'], $hall, $zoneBindings);
        $this->storeLabels($schema['labels'], $hall);

        return $hall;
    }

    /**
     * Store hall
     *
     * @param $schema
     * @param $building
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeHall($schema, $building)
    {
        return Hall::create([
            'name' => $schema['name'],
            'kartina_id' => $schema['id'],
            'building_id' => $building->id,
        ]);
    }

    /**
     * Store zones
     *
     * @param $zones
     * @param $hall
     * @return array
     */
    public function storeZones($zones, $hall)
    {
        $bindings = [];
        foreach ($zones as $zoneData) {
            $zone = Zone::create([
                'hall_id' => $hall->id,
                'name' => $zoneData['name'],
                'kartina_id' => $zoneData['id'],
            ]);

            $bindings[$zoneData['id']] = $zone->id;
        }

        return $bindings;
    }

    /**
     * Store places
     *
     * @param $places
     * @param $hall
     * @param $zoneBinds
     */
    public function storePlaces($places, $hall, $zoneBinds)
    {
        $data = [];
        foreach ($places as $place) {
            $data[] = [
                'row' => $place['row'],
                'num' => $place['num'],
                'text' => $place['text'],
                'kartina_id' => $place['id'],
                'template' => $place['template'],
                'x' => (double)$place['x'],
                'y' => (double)$place['y'],
                'width' => (double)$place['width'],
                'height' => (double)$place['height'],
                'path' => $place['path'],
                'rotate' => (double)$place['rotate'],
                'zone_id' => $zoneBinds[$place['zone']] ?? null,
                'hall_id' => $hall->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        collect($data)->chunk(500)->each(function($chunk) {
            Place::insert($chunk->toArray());
        });
    }

    /**
     * Store labels
     *
     * @param $labels
     * @param $hall
     */
    public function storeLabels($labels, $hall)
    {
        $data = [];
        foreach ($labels as $label) {
            $data[] = [
                'x' => (double)$label['x'],
                'y' => (double)$label['y'],
                'hall_id' => $hall->id,
                'is_bold' => $label['IsBold'],
                'is_italic' => $label['IsItalic'],
                'text' => $label['text'],
                'layer' => $label['zIndex'],
                'rotation' => (double)$label['rotation'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        collect($data)->chunk(500)->each(function ($chunk) {
            Label::insert($chunk->toArray());
        });
    }

    public function parse(ParseEvent $parseEvent)
    {
        $event = $this->getEvents($parseEvent->kartina_id);

        $this->storeBlueprintFromEvent($event);
        $this->storeEventLocally($event);

        ParseEvent::parsed($parseEvent->kartina_id);
    }

    protected function storeEventLocally($event)
    {
        $schema = $this->getHallSchema($event['EventId']);
        $building = $this->findBuildingByEventData($event);
        $hall = $this->storeHallSchema($schema, $building);

        $eventModel = Event::create([
            'name' => $event['EventName'],
            'date' => Carbon::createFromFormat("d.m.Y H:i", $event['EventDate'])->format(config('admin.date_format') . ' ' . config('admin.time_format_hm')),
            'is_active' => 1,
            'hall_id' => $hall->id,
            'kartina_id' => $event['EventId'],
        ]);
        if ($event['EventImageBig']) {
            $data = file_get_contents($this->host . $event['EventImageBig']);
            $file = $this->uploadService->storeFromData($data, $eventModel);
            $eventModel->eventImage()->associate($file);
            $eventModel->save();
        }
    }

    public function storeBlueprintFromEvent($event, $returnParsedSchema = true)
    {
        $schema = $this->getHallSchema($event['EventId']);
        $building = $this->findBuildingByEventData($event);
        $blueprint = $this->storeHallBlueprint($schema, $building);

        return $returnParsedSchema ?  $blueprint : $schema;
    }

    public function storeHallBlueprint($schema, $building)
    {
        if ($revision = $this->getBlueprintRevision($schema, $building)) return $revision;

        return $this->storeBlueprintRevision($schema, $building);
    }

    protected function getBlueprintRevision($schema, $building)
    {
        $haystack = HallBlueprint
            ::with([
                'placeBlueprints',
                'zoneBlueprints',
                'labelBlueprints',
            ])
            ->whereBuildingId($building->id)
            ->whereName($schema['name'])
            ->get();

        foreach ($haystack as $item) {
            if (!$this->haveDelta($schema, $item)) {
                return $item;
            }
        }

        return null;
    }

    protected function storeBlueprintRevision($schema, $building)
    {
        $number = HallBlueprint::whereBuildingId($building->id)->whereName($schema['name'])->count() + 1;

        $hall = HallBlueprint::create([
            'name' => $schema['name'],
            'kartina_id' => 0,
            'building_id' => $building->id,
            'revision' => $number,
        ]);

        $zoneBinds = [];
        foreach ($schema['zones'] as $zone) {
            $zoneBinds[$zone['id']] = $this->storeBlueprintZone($zone, $hall);
        }

        $this->storeBlueprintPlaces($schema['places'], $zoneBinds, $hall);
        $this->storeBlueprintLabels($schema['labels'], $hall);
    }

    protected function storeBlueprintZone($zoneData, $hallBlueprint)
    {
        $zone = ZoneBlueprint::create([
            'hall_blueprint_id' => $hallBlueprint->id,
            'name' => $zoneData['name'],
            'kartina_id' => 0,
        ]);

        return $zone->id;
    }

    protected function storeBlueprintPlaces($places, $zoneBinds, $hallBlueprint)
    {
        $data = [];
        foreach ($places as $place) {
            $data[] = [
                'row' => $place['row'],
                'num' => $place['num'],
                'text' => $place['text'],
                'kartina_id' => 0,
                'template' => $place['template'],
                'x' => (double)$place['x'],
                'y' => (double)$place['y'],
                'width' => (double)$place['width'],
                'height' => (double)$place['height'],
                'path' => $place['path'],
                'rotate' => (double)$place['rotate'],
                'zone_blueprint_id' => $zoneBinds[$place['zone']] ?? null,
                'hall_blueprint_id' => $hallBlueprint->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        collect($data)->chunk(500)->each(function($chunk) {
            PlaceBlueprint::insert($chunk->toArray());
        });

    }

    protected function storeBlueprintLabels($labels, $hallBlueprint)
    {
        $data = [];
        foreach ($labels as $label) {
            $data[] = [
                'x' => (double)$label['x'],
                'y' => (double)$label['y'],
                'hall_blueprint_id' => $hallBlueprint->id,
                'is_bold' => $label['IsBold'],
                'is_italic' => $label['IsItalic'],
                'text' => $label['text'],
                'layer' => $label['zIndex'],
                'rotation' => (double)$label['rotation'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        collect($data)->chunk(500)->each(function ($chunk) {
            LabelBlueprint::insert($chunk->toArray());
        });
    }

    protected function haveDelta($kartinaBlueprint, $storedBlueprint)
    {
        $zones = $storedBlueprint->zoneBlueprints;
        if (count($kartinaBlueprint['zones']) !== $zones->count()) return true;
        $zoneBinds = [];
        foreach ($kartinaBlueprint['zones'] as $kartinaZone) {
            $z = $zones->where('name', $kartinaZone['name'])->first();

            if (!$z) return true;

            $zoneBinds[$kartinaZone['id']] = $z->id;
        }

        $places = $storedBlueprint->placeBlueprints;
        if (count($kartinaBlueprint['places']) !== $places->count()) return true;
        foreach ($kartinaBlueprint['places'] as $kartinaPlace) {
            $p = $places
                ->where('row', $kartinaPlace['row'])
                ->where('num', $kartinaPlace['num'])
                ->where('zone_blueprint_id', $zoneBinds[$kartinaPlace['zone']] ?? null)
                ->where('text', $kartinaPlace['text'])
                ->where('template', $kartinaPlace['template'])
                ->where('x', $kartinaPlace['x'])
                ->where('y', $kartinaPlace['y'])
                ->where('width', $kartinaPlace['width'])
                ->where('height', $kartinaPlace['height'])
                ->first()
            ;

            if (!$p) return true;
        }

        return false;
    }

    public function findBuildingByEventData($event)
    {
        $building = Building
            ::whereName($event['BuildingName'])
            ->whereAddress($event['BuildingAddress'])
            ->whereHas('city', function($q) use($event) {
                $q->where('name', $event['City']);
            })
            ->first()
        ;

        if (empty($building)) $building = $this->storeBuildingFromEventData($event);

        return $building;
    }

    protected function storeBuildingFromEventData($event)
    {
        $city = $this->getCityByEventData($event);

        $building = Building::create([
            'name' => $event['BuildingName'],
            'address' => $event['BuildingAddress'],
            'kartina_id' => $event['BuildingId'],
            'city_id' => $city->id,
        ]);

        return $building;
    }

    protected function getCityByEventData($event)
    {
        $city = City::whereName($event['City'])->first();

        if (empty($city)) $city = $this->storeCityFromEventData($event);

        return $city;
    }

    protected function storeCityFromEventData($event)
    {
        $city = $this->getCities()->where('name', $event['City'])->first();
        $country = Country::whereName($city['countryName'])->first();

        $entity = City::create([
            'name' => $event['City'],
            'kartina_id' => $event['CityId'],
            'country_id' => $country->id,
        ]);

        return $entity;
    }
}
