<?php

namespace App\Exports;


use App\Modules\Admin\Requests\ByPartnersReportRequest;
use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ByPartnerReportExport implements FromView, ShouldAutoSize
{
    protected $_reportService;
    protected $_request;

    public function __construct(ReportService $reportService, ByPartnersReportRequest $request)
    {
        $this->_reportService = $reportService;
        $this->_request = $request;
    }

    public function view(): View
    {
        return $this->_reportService->exportByPartnerData($this->_request);
    }
}