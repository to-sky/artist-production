<?php

namespace App\Modules\Admin\Controllers;

use App\Exports\ByBookkeeperReportExport;
use App\Exports\ByBookkeeperTicketsExport;
use App\Exports\ByPartnerReportExport;
use App\Exports\EventSoldTicketsExport;
use App\Exports\OverallReportExport;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Modules\Admin\Requests\ByBookkepperReportRequest;
use App\Modules\Admin\Requests\ByPartnersReportRequest;
use App\Modules\Admin\Requests\EventOptionsRequest;
use App\Modules\Admin\Requests\OverallReportRequest;
use App\Modules\Admin\Services\RedirectService;
use App\Services\ReportService;
use Carbon\Carbon;
use Excel;

class ReportController extends AdminController
{
    protected $_reportService;

    public function __construct(RedirectService $redirectService, ReportService $reportService)
    {
        parent::__construct($redirectService);

        $this->_reportService = $reportService;
    }

    public function getEventsOptions(EventOptionsRequest $request)
    {
        return response()->json([
            'options' => $this->_reportService->getEventsByDatesRange(
                $request->get('event_range_start'),
                $request->get('event_range_end'),
                $request->get('event_name', [])
            ),
        ]);
    }

    public function byPartners()
    {
        $salesStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $salesEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
        $eventNames = ReportService::getEventNamesOptions();
        $partnerOptions = ReportService::getPartnersOptions();
        $excludeOptions = ReportService::getPartnersOptions(true);
        $orderStatusOptions = ReportService::getOrderStatusesOptions();
        $defaultStatuses = [
            Order::STATUS_CONFIRMED,
        ];

        return view('Admin::report.by_partner', compact(
            'salesStart',
            'salesEnd',
            'eventNames',
            'partnerOptions',
            'excludeOptions',
            'orderStatusOptions',
            'defaultStatuses'
        ));
    }

    public function getByPartnersData(ByPartnersReportRequest $request)
    {
        return $this->_reportService->displayByPartnerData($request);
    }

    public function exportByPartnerData(ByPartnersReportRequest $request)
    {
        $excel = new ByPartnerReportExport($this->_reportService, $request);
        $hash = md5(Carbon::now());

        return Excel::download($excel, "rep_partners_{$hash}.xlsx");
    }

    public function byBookkeepers()
    {
        $salesStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $salesEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
        $paymentMethodOptions = ReportService::getPaymentMethodOptions();
        $bookkeeperOptions = ReportService::getBookkeeperOptions();
        $ticketStatusOptions = ReportService::getTicketStatusesOptions();
        $defaultStatuses = [
            Ticket::SOLD,
        ];

        return view('Admin::report.by_bookkeeper', compact(
            'salesStart',
            'salesEnd',
            'paymentMethodOptions',
            'bookkeeperOptions',
            'ticketStatusOptions',
            'defaultStatuses'
        ));
    }

    public function getByBookkeepersData(ByBookkepperReportRequest $request)
    {
        return $this->_reportService->displayByBookkeeperData($request);
    }

    public function exportByBookkeeperData(ByBookkepperReportRequest $request)
    {
        $excel = new ByBookkeeperReportExport($this->_reportService, $request);
        $hash = md5(Carbon::now());

        return Excel::download($excel, "rep_bookkeepers_{$hash}.xlsx");
    }

    public function exportByBookkeepersTickets(ByBookkepperReportRequest $request)
    {
        $excel = new ByBookkeeperTicketsExport($this->_reportService, $request);
        $hash = md5(Carbon::now());

        return Excel::download($excel, "rep_bookkeepers_tickets_{$hash}.xlsx");
    }

    public function overall()
    {
        $salesStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $salesEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
        $eventNames = ReportService::getEventNamesOptions();
        $partnerOptions = ReportService::getPartnersOptions();
        $excludeOptions = ReportService::getPartnersOptions(true);
        $sortOptions = ReportService::getOverallReportSortOptions();
        $paymentMethodOptions = ReportService::getPaymentMethodOptions();
        $shippingOptions = ReportService::getDeliveryMethodOptions();

        return view('Admin::report.overall', compact(
            'salesStart',
            'salesEnd',
            'eventNames',
            'partnerOptions',
            'excludeOptions',
            'sortOptions',
            'paymentMethodOptions',
            'shippingOptions'
        ));
    }

    public function getOverallData(OverallReportRequest $request)
    {
        return $this->_reportService->displayOverallData($request);
    }

    public function exportOverallData(OverallReportRequest $request)
    {
        $excel = new OverallReportExport($this->_reportService, $request);
        $hash = md5(Carbon::now());

        return Excel::download($excel, "rep_overall_{$hash}.xlsx");
    }

    public function exportTicketSales(Event $event)
    {
        $excel = new EventSoldTicketsExport($this->_reportService, $event);
        $hash = md5(Carbon::now());

        return Excel::download($excel, "rep_overall_tickets_{$event->id}_{$hash}.xlsx");
    }

    public function exportTicketsUnsold(Event $event)
    {
        return $this->_reportService->displayEventUnsoldTickets($event);
    }
}
