<?php

namespace App\Console\Commands;

use App\Libs\Kartina\Api;
use App\Models\ParseEvent;
use Illuminate\Console\Command;

class ParseEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kartina:parse-events {--limit=5} {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse events for discovered events from kartina.tv';

    protected $api;

    /**
     * Create a new command instance.
     *
     * @param Api $api
     *
     * @return void
     */
    public function __construct(Api $api)
    {
        parent::__construct();

        $this->api = $api;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($id = $this->option('id')) {
            $parsedEvent = ParseEvent::whereKartinaId($id)->first();

            if (empty($parsedEvent)) $this->error("Event with id='$id' not found");

            $this->api->parse($parsedEvent);

            return;
        }

        $limit = $this->option('limit');

        $parsedEvents = ParseEvent::whereIsParsed(0)->limit($limit)->get();

        foreach ($parsedEvents as $parsedEvent) {
            $this->api->parse($parsedEvent);
        }
    }
}
