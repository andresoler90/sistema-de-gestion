<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        truncate('states');

        $states = [
            'Abierto',
            'Cerrado',
            'Escalado al cliente',
            'Cancelado',
            'Reabierto',
            'Anulado',
            'Suspendido',
        ];

        foreach($states as $value) {
            $state = new State();
            $state->name = $value;
            $state->save();
        }
    }
}
