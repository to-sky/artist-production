<?php

namespace App\Libs\Kartina;

use App\Models\Building;
use App\Models\City;
use App\Models\Hall;
use App\Models\ParseEvent;
use App\Models\Place;
use App\Models\Zone;
use Cache;
use Log;

class Api extends Base
{
    /**
     * Auth token
     *
     * @var void
     */
    protected $auth;

    /**
     * API urls
     *
     * @var array
     */
    protected $urls;

    /**
     * Api constructor.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct()
    {
        parent::__construct();

        $this->urls = config('kartina.api');
        $this->auth = $this->getAuth();
    }

    /**
     * Get auth token
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAuth()
    {
        $auth = $this->sendRequest($this->urls[__FUNCTION__], ['__uid' => env('KARTINA_UID')]);

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
            $this->sendAuthRequest($this->urls[__FUNCTION__])
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
            $this->sendAuthRequest($this->urls[__FUNCTION__])
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
            $this->sendAuthRequest($this->urls[__FUNCTION__])
        );
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
        $schema = $this->sendAuthRequest($this->urls[__FUNCTION__], ['event' => $kartinaEventId]);

        return $this->filterRequest($schema, true);
    }

    /**
     * Store kartina buildings to cache
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeBuildingsToCache()
    {
        return Cache::add('kartina_buildings', $this->getBuildings(), 1440);
    }

    /**
     * Store kartina halls to cache
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeHallsToCache()
    {
        return Cache::add('kartina_halls', $this->getHalls(), 1440);
    }

    /**
     * Get kartina buildings from cache
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBuildingsFromCache()
    {
        $buildings = Cache::get('kartina_buildings');

        if (is_null($buildings)) {
            $this->storeBuildingsToCache();

            return $this->getBuildingsFromCache();
        }

        return collect($buildings);
    }

    /**
     * Get kartina halls from cache
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHallsFromCache()
    {
        $halls = Cache::get('kartina_halls');

        if (is_null($halls)) {
            $this->storeHallsToCache();

            return $this->getHallsFromCache();
        }

        return collect($halls);
    }

    /**
     * Store hall schema (hall, places, zones)
     *
     * @param $kartinaEventId
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeHallSchema($kartinaEventId)
    {
        $schema = $this->getHallSchema($kartinaEventId);

        $hall = $this->storeHall($schema['name'], $schema['id']);
        $zones = $this->storeZones($schema['zones'], $hall->id);
        $places = $this->storePlaces($schema['places'], $hall->id);

        Log::channel('parser')->info("Data from event $kartinaEventId get hall " . $schema['name'] .
            " with " . count($zones) . " zones and " . count($places) . " places ");

        ParseEvent::parsed($kartinaEventId);

        return true;
    }

    /**
     * Get kartina building related on kartina hall id
     *
     * @param $kartinaHallId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBuildingByHallId($kartinaHallId)
    {
        $hall = $this->getHallsFromCache()->firstWhere('id', $kartinaHallId);

        return $this->getBuildingsFromCache()->firstWhere('id', $hall['buildingId']);
    }

    /**
     * Store building
     *
     * @param $kartinaHallId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeBuilding($kartinaHallId)
    {
        $kartinaBuilding = $this->getBuildingByHallId($kartinaHallId);

        $params = [
            'name' => $kartinaBuilding['name'],
            'address' => $kartinaBuilding['address'],
            'city_id' => City::where('kartina_id', $kartinaBuilding['cityId'])->first()->id
        ];

        return Building::firstOrCreate($params, $params + [
            'kartina_id' => $kartinaBuilding['id']
        ]);
    }

    /**
     * Store hall
     *
     * @param $hallName
     * @param $kartinaHallId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeHall($hallName, $kartinaHallId)
    {
        $building = $this->storeBuilding($kartinaHallId);

        $params = [
            'name' => $hallName,
            'building_id' => $building->id,
        ];

        return Hall::firstOrCreate($params, $params + [
                'kartina_id' => $kartinaHallId
        ]);
    }

    /**
     * Store zones
     *
     * @param $zones
     * @param $hallId
     * @return \Illuminate\Support\Collection
     */
    public function storeZones($zones, $hallId)
    {
        return collect($zones)->map(function ($zone) use ($hallId) {
            return Zone::firstOrCreate([
                'name' => $zone['name'],
                'hall_id' => $hallId,
                'kartina_id' => $zone['id']
            ]);
        });
    }

    /**
     * Store places
     *
     * @param $places
     * @param $hallId
     * @return \Illuminate\Support\Collection
     */
    public function storePlaces($places, $hallId)
    {
        return collect($places)->map(function ($place) use ($hallId) {
            if ($place['template'] != 'scene') {
                $zoneId = Zone::where('kartina_id', $place['zone'])->first()->id ?? null;

                return Place::firstOrCreate([
                    'row' => $place['row'],
                    'num' => $place['num'],
                    'kartina_id' => $place['id'],
                    'text' => $place['text'],
                    'zone_id' => $zoneId,
                    'hall_id' => $hallId
                ]);
            }
        });
    }
}
