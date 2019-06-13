<?php

namespace App\Exports;

use App\Models\Profile;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ClientsExport implements WithEvents, FromCollection, WithHeadings, WithTitle
{

    use Exportable, RegistersEventListeners;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $clients = Profile::all();
        $clients->transform(function ($item, $key) {
            return [
                $item['id'],
                $item['fullname'],
                $item['email'],
                $item['phone'],
                $item['type']
            ];
        });

        return $clients;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'id',
            __('Full name'),
            __('Email'),
            __('Phone'),
            __('Type'),
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Clients list');
    }

    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->getColumnDimension('A')->setWidth(15);
        $event->sheet->getColumnDimension('B')->setWidth(50);
        $event->sheet->getColumnDimension('C')->setWidth(30);
        $event->sheet->getColumnDimension('D')->setWidth(30);
        $event->sheet->getColumnDimension('E')->setWidth(15);
    }
}
