<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InitialInternalManagementReport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    public $analysts_tasks;
    public $stage_tasks;
    public $total;
    public $data;

    public function __construct($analysts_tasks,$stage_tasks,$total,$data)
    {
        $this->analysts_tasks = $analysts_tasks;
        $this->stage_tasks = $stage_tasks;
        $this->total = $total;
        $this->data = $data;
    }

    public function styles(Worksheet $sheet): array
    {

        return [
            1 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return __('Reporte-gestión-interna');
    }

    public function view(): View
    {
        return view('exports.internal_management.initial')
            ->with('analysts_tasks',$this->analysts_tasks)
            ->with('stage_tasks',$this->stage_tasks)
            ->with('total',$this->total)
            ->with('data',$this->data);
    }
}
