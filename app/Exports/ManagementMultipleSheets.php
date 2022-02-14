<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ManagementMultipleSheets implements WithMultipleSheets
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

    public function sheets(): array
    {
        return [
            'sheets_1' => new ManagementReport($this->registers_states,$this->registers_stages,$this->registers_responsables,$this->total,$this->data),
            'sheets_2' => new ManagementDetailReport($this->registers_states,$this->registers_stages,$this->registers_responsables,$this->total,$this->data),
        ];
    }

}
