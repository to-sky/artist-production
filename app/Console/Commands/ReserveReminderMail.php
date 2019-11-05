<?php

namespace App\Console\Commands;

use App\Mail\DynamicMails\ReservationReminderMail;
use App\Models\Order;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReserveReminderMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artist:reserve-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends reminder for reserve after 4 days without payment';

    protected $mailService;

    /**
     * Create a new command instance.
     *
     * @param MailService $mailService
     *
     * @return void
     */
    public function __construct(MailService $mailService)
    {
        parent::__construct();

        $this->mailService = $mailService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sendCount = 0;

        $orders = $this->getUnpaidReservationOrders();
        foreach ($orders as $order) {
            $sendCount += $this->sendReserveReminder($order);
        }

        $orders = $this->getUnpaidReservationsOrdersForSecondReminder();
        foreach ($orders as $order) {
            $sendCount += $this->sendSecondReserveReminder($order);
        }

        $this->output->text('Mails send: ' . $sendCount);
    }

    protected function getUnpaidReservationOrders()
    {
        $date = Carbon::now()->subDay(4);
        $start = $date->format('Y-m-d 00:00:00');
        $end = $date->format('Y-m-d 23:59:59');

        $orders = Order
            ::whereIn('status' , [Order::STATUS_RESERVE, Order::STATUS_PENDING])
            ->whereBetween('created_at', [$start, $end])
            ->get()
        ;

        return $orders;
    }

    protected function sendReserveReminder(Order $order)
    {
        return $this->mailService->send(new ReservationReminderMail($order->user, $order));
    }

    protected function getUnpaidReservationsOrdersForSecondReminder()
    {
        $date = Carbon::now()->subDay(10);
        $start = $date->format('Y-m-d 00:00:00');
        $end = $date->format('Y-m-d 23:59:59');

        $orders = Order
            ::whereIn('status' , [Order::STATUS_RESERVE, Order::STATUS_PENDING])
            ->whereBetween('created_at', [$start, $end])
            ->get()
        ;

        return $orders;
    }

    protected function sendSecondReserveReminder(Order $order)
    {
        return $this->mailService->send(new ReservationReminderMail($order->user, $order));
    }
}
