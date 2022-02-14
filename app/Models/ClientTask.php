<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ClientTask
 *
 * @property int $id
 * @property int $clients_id Cliente responsable de la tarea
 * @property int $stage_tasks_id Responsable de la asociacion
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Client|null $client
 * @property-read \App\Models\StageTask|null $stage_task
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask newQuery()
 * @method static \Illuminate\Database\Query\Builder|ClientTask onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask whereClientsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask whereStageTasksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ClientTask withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ClientTask withoutTrashed()
 * @mixin \Eloquent
 */
class ClientTask extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded = [];

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'clients_id');
    }

    public function stage_task()
    {
        return $this->hasOne(StageTask::class, 'id', 'stage_tasks_id');
    }
}
