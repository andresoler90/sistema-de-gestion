<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ANSReport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    public $register_times;
    public $management_time;
    public $totalRegisters;
    public $data;

    public function __construct($register_times,$management_time,$totalRegisters,$data)
    {
        $this->register_times = $register_times;
        $this->management_time = $management_time;
        $this->totalRegisters = $totalRegisters;
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
        return __('Reporte-ANS');
    }

    public function view(): View
    {
        return view('exports.ans.report')
            ->with('register_times',$this->register_times)
            ->with('management_time',$this->management_time)
            ->with('totalRegisters',$this->totalRegisters)
            ->with('data',$this->data);
    }
}
