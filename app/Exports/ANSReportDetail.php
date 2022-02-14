<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ANSReportDetail implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    public $register;
    public $register_times;
    public $total;

    public function __construct($register,$register_times,$total)
    {
        $this->register = $register;
        $this->register_times = $register_times;
        $this->total = $total;
    }

    public function styles(Worksheet $sheet): array
    {

        return [
            1 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return __('Reporte-ANS-Detalle');
    }

    public function view(): View
    {
        return view('exports.ans.report_detail')
            ->with('register',$this->register)
            ->with('register_times',$this->register_times)
            ->with('total',$this->total);
    }
}
