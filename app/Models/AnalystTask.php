<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\AnalystTask
 *
 * @property int $id
 * @property int $stage_tasks_id ID del analista
 * @property int $analyst_id ID de la tarea
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask newQuery()
 * @method static \Illuminate\Database\Query\Builder|AnalystTask onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask whereAnalystId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask whereStageTasksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnalystTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AnalystTask withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AnalystTask withoutTrashed()
 * @mixin \Eloquent
 */
class AnalystTask extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded = [];
}
