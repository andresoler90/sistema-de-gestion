<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Stage
 *
 * @property int $id
 * @property string $name Nombre de la etapa
 * @property int $order orden de la etapa
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StageTask[] $tasks
 * @property-read int|null $tasks_count
 * @method static \Illuminate\Database\Eloquent\Builder|Stage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stage newQuery()
 * @method static \Illuminate\Database\Query\Builder|Stage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Stage query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Stage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Stage withoutTrashed()
 * @mixin \Eloquent
 * @property int $percentage Porcentaje de avance que representa la etapa
 * @property-read mixed $accumulated
 * @method static \Illuminate\Database\Eloquent\Builder|Stage wherePercentage($value)
 */
class Stage extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    public function tasks()
    {
        return $this->hasMany(StageTask::class, 'stages_id', 'id');
    }

    public function clientTasks($client_id,$have=true)
    {

        if($have){ // Tareas que tiene un cliente en una etapa

            return ClientTask::join('stage_tasks','client_tasks.stage_tasks_id','stage_tasks.id')
            ->where([
                ['client_tasks.clients_id',$client_id],
                ['stage_tasks.stages_id',$this->id]
                ])->get();
        }else{ // Tareas que no tiene un cliente en una etapa
            $tasks = ClientTask::select('stage_tasks_id')->where('clients_id',$client_id)->get();
            return StageTask::where('stages_id',$this->id)->whereNotIn('id',$tasks)->get();
        }
    }

    public function getAccumulatedAttribute()
    {
        $accumulated = 0;
        $stages = Stage::all();

        foreach ($stages as $stage) {
            $accumulated = $accumulated + $stage->percentage;
            if($stage->id == $this->id){
                break;
            }
        }
        return $accumulated;
    }


    public function userTasks($user_id,$have=true)
    {

        if($have){ // Tareas que tiene un analista en una etapa

            return AnalystTask::join('stage_tasks','analyst_tasks.stage_tasks_id','stage_tasks.id')
            ->where([
                ['analyst_tasks.analyst_id',$user_id],
                ['stage_tasks.stages_id',$this->id]
                ])->get();
        }else{ // Tareas que no tiene un analista en una etapa
            $tasks = AnalystTask::select('stage_tasks_id')->where('analyst_id',$user_id)->get();
            return StageTask::where('stages_id',$this->id)
                ->whereNotIn('id',$tasks)
                ->whereNotIn('name',['ve_task_5','ca_task_2','ca_task_3','ca_task_4','ca_task_5'])
                ->get();
        }
    }
}
