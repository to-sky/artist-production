<?php

namespace App\Exports;


use App\Modules\Admin\Requests\OverallReportRequest;
use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OverallReportExport implements FromView, ShouldAutoSize
{
    protected $_reportService;
    protected $_request;

    public function __construct(ReportService $reportService, OverallReportRequest $request)
    {
        $this->_reportService = $reportService;
        $this->_request = $request;
    }

    public function view(): View
    {
        return $this->_reportService->exportOverallData($this->_request);
    }
}