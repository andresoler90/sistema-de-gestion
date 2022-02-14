<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\RegisterTask
 *
 * @property int $id
 * @property int $client_tasks_id Id tareas del cliente
 * @property int $registers_id Id de la solicitud
 * @property int|null $analyst_users_id Id del analista
 * @property string $priority Prioridad de la tarea CPC: Crítica por Calidad / CRI:Crítica / ALT: Alta / MED: Media / BAJ: Baja
 * @property string|null $start_date Fecha de inicio
 * @property string|null $end_date Fecha de finalización
 * @property string $status Estado de la tarea A: Abierta / C: Cerrada
 * @property string $management_status Estado de gestión ATI: A tiempo / ATR: Atrasada
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $analyst
 * @property-read \App\Models\ClientTask|null $client_task
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RegisterEvent[] $events
 * @property-read int|null $events_count
 * @property-read mixed $priority_name
 * @property-read mixed $status_name
 * @property-read mixed $task
 * @property-read \App\Models\Register|null $register
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask newQuery()
 * @method static \Illuminate\Database\Query\Builder|RegisterTask onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereAnalystUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereClientTasksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereManagementStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereRegistersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RegisterTask withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RegisterTask withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RegisterSalesForce[] $salesforce
 * @property-read int|null $salesforce_count
 * @property string $analyst_notification Se le notifico al analista, S:SI / N:NO
 * @property string $coordinator_notification Se le notifico al coordinador, S:SI / N:NO
 * @property-read mixed $last_event
 * @property-read mixed $management_status_name
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask addAnalyst()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask addClientTask()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask addRegisterClient()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask addRegisterEvent()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask addStage()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereAnalystNotification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterTask whereCoordinatorNotification($value)
 */
class RegisterTask extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded = [];
    protected $dates = ['start_date'];


    public function client_task()
    {
        return $this->hasOne(ClientTask::class, 'id', 'client_tasks_id')->withTrashed();
    }

    public function register()
    {
        return $this->hasOne(Register::class, 'id', 'registers_id');
    }

    public function getTaskAttribute()
    {
        return $this->client_task->stage_task;
    }

    public function analyst()
    {
        return $this->hasOne(User::class, 'id', 'analyst_users_id');
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 'A':
                return __('Abierto');
                break;

            case 'C':
                return __('Cerrado');
                break;
        }
    }

    public function getPriorityNameAttribute()
    {
        switch ($this->priority) {
            case 'CPC':
                return __('Critico por calidad');
                break;
            case 'CPM':
                return __('Critico por modificación');
                break;
            case 'CRI':
                return __('Critico');
                break;

            case 'ALT':
                return __('Alto');
                break;

            case 'MED':
                return __('Media');
                break;

            case 'BAJ':
                return __('Bajo');
                break;
        }
    }

    public function getManagementStatusNameAttribute()
    {
        switch ($this->management_status) {
            case 'ATI':
                return __('A tiempo');
                break;

            case 'ATR':
                return __('Atrasado');
                break;
        }
    }

    public function events()
    {
        return $this->hasMany(RegisterEvent::class, 'register_tasks_id', 'id');
    }

    public function getLastEventAttribute()
    {
        $event = RegisterEvent::where('register_tasks_id', $this->id)
            ->orderBy('created_at', 'DESC')
            ->first();

        return $event;
    }

    public function salesforce()
    {
        return $this->hasMany(RegisterSalesForce::class, 'register_id', 'registers_id');
    }

    public function scopeAddClientTask($query)
    {
        return $query->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id');
    }

    public function scopeAddRegisterEvent($query)
    {
        return $query->join('register_events', 'register_events.register_tasks_id', '=', 'register_tasks.id');
    }

    public function scopeAddStage($query)
    {
        return $query->join('stage_tasks', 'stage_tasks.id', '=', 'register_events.stage_tasks_id')
                     ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id');
    }

    public function scopeAddRegisterClient($query)
    {
        return $query->join('registers', 'registers.id', '=', 'register_tasks.registers_id')
                     ->join('clients', 'clients.id', '=', 'registers.clients_id');
    }

    public function scopeAddAnalyst($query)
    {
        return $query->join('users', 'users.id', '=', 'register_tasks.analyst_users_id');
    }

    public function scopeAddClientTaskStage($query)
    {
        return $query->join('client_tasks', 'client_tasks.id', '=', 'register_tasks.client_tasks_id')
                     ->join('stage_tasks', 'client_tasks.stage_tasks_id', '=', 'stage_tasks.id')
                     ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id');

    }
}
