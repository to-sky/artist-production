<?php

namespace App\Exports;


use App\Models\Event;
use App\Models\Order;
use App\Services\ReportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventSoldTicketsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $_reportService;
    protected $_event;

    public function __construct(ReportService $reportService, Event $event)
    {
        $this->_reportService = $reportService;
        $this->_event = $event;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->_reportService->exportEventTickets($this->_event, [
            Order::STATUS_REALIZATION,
            Order::STATUS_CONFIRMED,
            Order::STATUS_RESERVE,
            Order::STATUS_CANCELED,
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('Zone'),
            __('Row'),
            __('Place'),
            __('Price'),
            __('Barcode'),
            __('Order'),
            __('Status'),
            __('Payment date'),
            __('Shipping method'),
            __('Payment method'),
            __('Partner'),
            __('Client'),
        ];
    }
}