<?php

namespace App\Libs\Kartina;

use Log;
use App\Models\ParseEvent;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class Parser extends Base
{
    public $host = 'https://biletkartina.tv';

    public $kartinaUrl = 'https://biletkartina.tv/api/ru/event/getbyfilter';

    protected $api = null;

    public function __construct()
    {
        parent::__construct();

        $this->api = new Api();
    }

    /**
     * Get event id's
     *
     * @return array
     */
    public function getEventIds()
    {
        $ids = $this->api->getEvents()->pluck('EventId')->toArray();

        return $ids;
    }

    /**
     * Store event id's
     *
     * @return \Illuminate\Support\Collection
     */
    public function storeEventId()
    {
        return collect($this->getEventIds())->map(function ($eventId) {
            return ParseEvent::firstOrCreate(['kartina_id' => $eventId])->wasRecentlyCreated;
        })->filter();
    }
}
