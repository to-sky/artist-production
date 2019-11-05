<?php

namespace App\Exports;


use App\Modules\Admin\Requests\PartnerReportRequest;
use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PartnerReportExport implements FromView, ShouldAutoSize
{
    protected $_reportService;
    protected $_request;

    public function __construct(ReportService $reportService, PartnerReportRequest $request)
    {
        $this->_reportService = $reportService;
        $this->_request = $request;
    }

    public function view(): View
    {
        return $this->_reportService->exportPartnerData($this->_request);
    }
}