<?php

namespace App\Console\Commands;

use App\Libs\Kartina\Parser;
use Illuminate\Console\Command;

class ParseEventIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kartina:parse-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all upcoming event id\'s from Kartina.tv';

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
     * @param Parser $parser
     */
    public function handle(Parser $parser)
    {
        $eventCount = $parser->storeEventId()->count();

        $result = $eventCount ? "$eventCount new events has been added." : "No new events.";

        $this->output->write($result.PHP_EOL);
    }
}
