<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QualityReport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    public $analysts_tasks;
    public $verification_tasks;
    public $total;
    public $data;

    public function __construct($analysts_tasks,$verification_tasks,$total,$data)
    {
        $this->analysts_tasks = $analysts_tasks;
        $this->verification_tasks = $verification_tasks;
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
        return __('Reporte-Calidad');
    }

    public function view(): View
    {
        return view('exports.quality.report')
            ->with('analysts_tasks',$this->analysts_tasks)
            ->with('verification_tasks',$this->verification_tasks)
            ->with('total',$this->total)
            ->with('data',$this->data);
    }
}
