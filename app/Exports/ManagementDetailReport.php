<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;


class ManagementDetailReport implements FromView, ShouldAutoSize, WithTitle
{
    public $registers_states;
    public $registers_stages;
    public $registers_responsables;
    public $total;
    public $data;

    public function __construct($registers_states,$registers_stages,$registers_responsables,$total,$data)
    {
        $this->registers_states = $registers_states;
        $this->registers_stages = $registers_stages;
        $this->registers_responsables = $registers_responsables;
        $this->total = $total;
        $this->data = $data;
    }

    public function title(): string
    {
        return __('Detalle');
    }

    public function view(): View
    {
        return view('exports.management.report')
            ->with('registers_states',$this->registers_states)
            ->with('registers_stages',$this->registers_stages)
            ->with('registers_responsables',$this->registers_responsables)
            ->with('total',$this->total)
            ->with('data',$this->data);
    }
}
