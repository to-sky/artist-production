<?php

namespace App\Exports;


use App\Modules\Admin\Requests\ByBookkepperReportRequest;
use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ByBookkeeperTicketsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $_reportService;
    protected $_request;

    public function __construct(ReportService $reportService, ByBookkepperReportRequest $request)
    {
        $this->_reportService = $reportService;
        $this->_request = $request;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->_reportService->getByBookkeeperData($this->_request, true);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('Event'),
            __('Bookkeeper'),
            __('Barcode'),
            __('Status'),
            __('Price'),
            __('Order'),
            __('Amount'),
            __('Discount'),
            __('Total'),
        ];
    }
}