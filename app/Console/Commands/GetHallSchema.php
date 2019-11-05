<?php

namespace App\Console\Commands;

use App\Events\ParseEventSaved;
use App\Libs\Kartina\Api;
use App\Models\ParseEvent;
use Illuminate\Console\Command;

class GetHallSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kartina:get-hall-schema 
                            {eventId? : Event id from Kartina.tv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get hall schema and store it to database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        if ($kartinaEventId = $this->argument('eventId')) {
            (new Api())->storeHallSchema($kartinaEventId);
        } else {
            ParseEvent::getNotParsedEvents()->each(function ($parseEvent) {
                event(new ParseEventSaved($parseEvent));
            });
        }
    }
}
