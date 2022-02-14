<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\RegisterEvent
 *
 * @property int $id
 * @property int $registers_id ID de la solicitud
 * @property int $states_id Estado de la solicitud
 * @property string $management Usuario quien se encuentra gestionando la solicitud PAR: Par / PRO: Proveedor / CLI: Cliente
 * @property string $description Descripción del evento
 * @property int $created_users_id Responsable del cambio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $finished_at Fecha de finalización del evento
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent newQuery()
 * @method static \Illuminate\Database\Query\Builder|RegisterEvent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereManagement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereRegistersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereStatesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RegisterEvent withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RegisterEvent withoutTrashed()
 * @mixin \Eloquent
 * @property int $stage_tasks_id ID de la tarea
 * @property int|null $register_tasks_id
 * @property-read mixed $management_name
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereRegisterTasksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent whereStageTasksId($value)
 * @property-read \App\Models\RegisterTask|null $register_task
 * @property-read \App\Models\StageTask|null $stage_task
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent addRegister()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterEvent addStageTask()
 */
class RegisterEvent extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'finished_at'
    ];

    public function getManagementNameAttribute()
    {
        switch ($this->management) {
            case 'PAR':
                return __('Par');
                break;

            case 'PRO':
                return __('Proveedor');
                break;
            case 'CLI':
                return __('Cliente');
                break;
        }
    }

    public function stage_task()
    {
        return $this->hasOne(StageTask::class, 'id','stage_tasks_id');
    }

    public function register_task()
    {
        return $this->hasOne(RegisterTask::class,'id','register_tasks_id');
    }

    public function scopeAddRegister($query)
    {
        return $query->join('registers', 'registers.id', '=', 'register_events.registers_id')
                     ->join('clients', 'clients.id', '=', 'registers.clients_id');


    }

    public function scopeAddStageTask($query)
    {
        return $query->join('stage_tasks', 'stage_tasks.id', '=', 'register_events.stage_tasks_id')
                     ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id');

    }

}
