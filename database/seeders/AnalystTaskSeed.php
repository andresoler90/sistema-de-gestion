<?php

namespace Database\Seeders;

use App\Models\AnalystTask;
use App\Models\StageTask;
use App\Models\User;
use Illuminate\Database\Seeder;


class AnalystTaskSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        truncate('analyst_tasks');

        $analyst_id = User::where('roles_id',2)->first();
        $analysts = User::where([['roles_id',2],['id','<>',$analyst_id->id]])->get();
        $stage_tasks = StageTask::whereIn('id',[4,5,6,7,8,15])->get();

        foreach ($stage_tasks as $task){
            $analyst_tasks = new AnalystTask ();
            $analyst_tasks->analyst_id = $analyst_id->id;
            $analyst_tasks->stage_tasks_id = $task->id;
            $analyst_tasks->created_users_id = 1;
            $analyst_tasks->save();
        }

        foreach ($analysts as $analyst) {

            $stage_tasks = StageTask::whereIn('id',[4,5,6,7,8,15])->get();
            $task_1 = $stage_tasks->random()->id;

            $analyst_tasks = new AnalystTask ();
            $analyst_tasks->analyst_id = $analyst->id;
            $analyst_tasks->stage_tasks_id = $task_1;
            $analyst_tasks->created_users_id = 1;
            $analyst_tasks->save();

            $stage_tasks = StageTask::whereIn('id',[4,5,6,7,8,15])
            ->where('id','<>',$task_1)->get();
            $task_2 = $stage_tasks->random()->id;

            $analyst_tasks = new AnalystTask ();
            $analyst_tasks->analyst_id = $analyst->id;
            $analyst_tasks->stage_tasks_id = $task_2;
            $analyst_tasks->created_users_id = 1;
            $analyst_tasks->save();
        }
    }
}
