<?php

namespace App\Modules\Admin\Controllers;

use App\Exports\ByPartnerReportExport;
use App\Models\Order;
use App\Modules\Admin\Requests\ByPartnersReportRequest;
use App\Modules\Admin\Requests\EventOptionsRequest;
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
}
