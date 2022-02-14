<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\StageTask
 *
 * @property int $id
 * @property string $name Nombre de la tarea
 * @property string|null $label Nombre que se mostrara en la etiqueta al usuario
 * @property int $stages_id Etapa de la tarea
 * @property float $estimated_time Tiempo estimado de cada tarea
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Stage|null $stage
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask newQuery()
 * @method static \Illuminate\Database\Query\Builder|StageTask onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereEstimatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereStagesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|StageTask withTrashed()
 * @method static \Illuminate\Database\Query\Builder|StageTask withoutTrashed()
 * @mixin \Eloquent
 * @property string $visible Tarea visible, sera tenido en cuenta al momento de verificar la documentacion asociada
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask visible()
 * @method static \Illuminate\Database\Eloquent\Builder|StageTask whereVisible($value)
 */
class StageTask extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded = [];

    public function stage()
    {
        return $this->hasOne(Stage::class, 'id', 'stages_id');
    }
    public function scopeVisible($query)
    {
        return $query->where('visible','S');
    }
}
