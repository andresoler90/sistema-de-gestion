<?php

namespace Database\Seeders;

use App\Models\Register;
use App\Models\RegisterEvent;
use Illuminate\Database\Seeder;

class RegisterEventSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $registers = Register::all();
        $key = 0;

        foreach($registers as $register){
            $register_event = new RegisterEvent();
            $register_event->registers_id= $register->id;
            $register_event->stage_tasks_id = 1;
            $register_event->states_id = 1;
            $register_event->register_tasks_id = $key + 1;
            $register_event->management = 'CLI';
            $register_event->description = 'Creación de la solicitud';
            $register_event->created_users_id = 1;
            $register_event->finished_at = now();
            $register_event->save();

            $register_event = new RegisterEvent();
            $register_event->registers_id= $register->id;
            $register_event->stage_tasks_id = 2;
            $register_event->states_id = 1;
            $register_event->register_tasks_id = $key + 2;
            $register_event->management = 'PAR';
            $register_event->description = 'Analisis de la solicitud';
            $register_event->created_users_id = 1;
            $register_event->finished_at = now();
            $register_event->save();

            $register_event = new RegisterEvent();
            $register_event->registers_id= $register->id;
            $register_event->stage_tasks_id = 4;
            $register_event->states_id = 1;
            $register_event->register_tasks_id = $key + 3;
            $register_event->management = 'PAR';
            $register_event->description = 'Matriz Documental, Seguimiento 1';
            $register_event->created_users_id = 1;
            $register_event->save();

            $key = $key + 3;
        }

    }
}
