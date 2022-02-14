<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Tracking
 *
 * @property int $id
 * @property int $registers_id Id de la solicitud
 * @property \Illuminate\Support\Carbon $date Fecha del seguimiento
 * @property string $contact_name Nombre del contacto
 * @property string|null $phone Teléfono
 * @property string|null $email Correo
 * @property string $type_contact Tipo de contacto
 * @property string $observations Observaciones
 * @property string $consecutive_code Código consecutivo
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking newQuery()
 * @method static \Illuminate\Database\Query\Builder|Tracking onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereConsecutiveCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereObservations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereRegistersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereTypeContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Tracking withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Tracking withoutTrashed()
 * @mixin \Eloquent
 * @property int $stage_tasks_id Id de la tarea
 * @property-read \App\Models\User|null $created_user
 * @property-read \App\Models\StageTask|null $stage_tasks
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking addDocumentManagements()
 * @method static \Illuminate\Database\Eloquent\Builder|Tracking whereStageTasksId($value)
 */
class Tracking extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $fillable = [
        'registers_id',
        'stage_tasks_id',
        'date',
        'contact_name',
        'phone',
        'email',
        'type_contact',
        'observations',
        'consecutive_code',
        'created_users_id',
    ];
    protected $dates = ['date'];

    public function scopeAddDocumentManagements($query)
    {
        return $query->join('document_managements','document_managements.trackings_id', '=', 'trackings.id');
    }

    public function stage_tasks()
    {
        return $this->hasOne(StageTask::class,'id','stage_tasks_id');
    }

    public function created_user()
    {
        return $this->hasOne(User::class,'id','created_users_id');
    }
}
