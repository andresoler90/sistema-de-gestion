<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientTask;
use App\Models\StageTask;
use Illuminate\Database\Seeder;


class ClientTaskSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        truncate('client_tasks');

        $clients = Client::all();
        $stage_tasks = StageTask::all();

        foreach ($clients as $client){
            foreach ($stage_tasks as $stage_task){
                $client_tasks = new ClientTask();
                $client_tasks->clients_id = $client->id;
                $client_tasks->stage_tasks_id = $stage_task->id;
                $client_tasks->created_users_id = 1;
                $client_tasks->save();
            }
        }
    }
}
