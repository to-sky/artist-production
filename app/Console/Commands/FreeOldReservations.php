<?php

namespace App\Console\Commands;

use App\Services\TicketService;
use Illuminate\Console\Command;

class FreeOldReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artist:free_reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Free tickets with outstanding reservations';

    protected $ticketsService;

    /**
     * Create a new command instance.
     *
     * @param TicketService $ticketService
     * @return void
     */
    public function __construct(TicketService $ticketService)
    {
        parent::__construct();

        $this->ticketsService = $ticketService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->ticketsService->freeByTimeout();
    }
}
