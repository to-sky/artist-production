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

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get html content from request
     *
     * @param $url
     * @return array|string
     */
    public function getHtmlFromUrl($url)
    {
        try {
            $response = $this->client->request('GET', $url);
        } catch (GuzzleException $e) {
            Log::channel('parser')->alert($e->getMessage());

            return null;
        }

        return $response->getBody()->getContents();
    }

    /**
     * Get event url's from main page
     *
     * @return array
     */
    public function getEventUrls()
    {
        $html = $this->getHtmlFromUrl($this->kartinaUrl);

        $crawler = new Crawler($html);

        $urls = $crawler->filterXPath('//div[@class="schedule-item_main-info"]//a/@href')
            ->each(function (Crawler $node) {
                return $this->host . $node->text();
            });

        return array_unique($urls);
    }

    /**
     * Get event id's from url
     *
     * @param string $url
     * @return array
     */
    public function getEventIdFromUrl($url)
    {
        $html = $this->getHtmlFromUrl($url);

        $crawler = new Crawler($html);

        $eventIds = $crawler->filterXPath('//a[contains(@href, "event_id")]/@href')
            ->evaluate('substring-after(substring-before(., "&"), "event_id=")');

        return $eventIds;
    }

    /**
     * Get event id's
     *
     * @return array
     */
    public function getEventIds()
    {
        $eventIds = [];
        foreach ($this->getEventUrls() as $url) {
            $eventIds = array_merge($eventIds, $this->getEventIdFromUrl($url));
        }

        return array_filter($eventIds);
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
