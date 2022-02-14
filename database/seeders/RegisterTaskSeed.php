<?php

namespace Database\Seeders;

use App\Http\Controllers\Libs\ProcessFlow;
use App\Models\ClientTask;
use App\Models\Register;
use App\Models\RegisterTask;
use Illuminate\Database\Seeder;

class RegisterTaskSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client_tasks = ClientTask::where([['stage_tasks_id',1],['clients_id','<>',1]])->get();

        foreach ($client_tasks as $key => $client_task) {
            $register_task = new RegisterTask();
            $register_task->client_tasks_id = $client_task->id;
            $register_task->registers_id = $key+1;
            $register_task->start_date = now();
            $register_task->end_date = now();
            $register_task->status = 'C';
            $register_task->management_status = 'ATI';
            $register_task->created_users_id = 1;
            $register_task->save();

            $register_task = new RegisterTask();
            $register_task->client_tasks_id = $client_task->id + 1;
            $register_task->registers_id = $key+1;
            $register_task->start_date = now();
            $register_task->end_date = now();
            $register_task->status = 'C';
            $register_task->management_status = 'ATI';
            $register_task->created_users_id = 1;
            $register_task->save();

            $register_task = new RegisterTask();
            $register_task->client_tasks_id = $client_task->id + 3;
            $register_task->registers_id = $key+1;
            $process_flow = new ProcessFlow();
            $register = Register::find($register_task->registers_id);
            $register_task->analyst_users_id = $process_flow->analyticsAvaible(4,$register->identification_number);
            $register_task->start_date = now();
            $register_task->status = 'A';
            $register_task->management_status = 'ATI';
            $register_task->created_users_id = 1;
            $register_task->save();
        }
    }
}
