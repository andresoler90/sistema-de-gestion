<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Verification
 *
 * @property int $id
 * @property int $registers_id ID de la solicitud
 * @property int $register_tasks_id ID de la tarea
 * @property int $client_documents_id ID del documento
 * @property string $satisfy Cumple, S:Si / N:No
 * @property string|null $outcome Resultado de la verificación
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ClientDocument|null $client_document
 * @property-read mixed $satisfy_name
 * @property-read \App\Models\RegisterEvent|null $register_event
 * @property-read \App\Models\RegisterTask|null $register_task
 * @method static \Illuminate\Database\Eloquent\Builder|Verification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Verification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Verification notVerified()
 * @method static \Illuminate\Database\Query\Builder|Verification onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Verification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Verification verified()
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereClientDocumentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereOutcome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereRegisterTasksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereRegistersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereSatisfy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Verification withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Verification withoutTrashed()
 * @mixin \Eloquent
 */
class Verification extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded  = [];

    public function getSatisfyNameAttribute()
    {
        switch ($this->satisfy) {
            case 'S':
                return __('Pasa');
                break;

            case 'N':
                return __('No Pasa');
                break;
        }
    }

    public function scopeVerified($query)
    {
        return $query->where('satisfy', 'S');

    }

    public function scopeNotVerified($query)
    {
        return $query->where('satisfy', 'N');

    }

    public function client_document()
    {
        return $this->hasOne(ClientDocument::class, 'id', 'client_documents_id');
    }

    public function register_task()
    {
        return $this->hasOne(RegisterTask::class,'id','register_tasks_id');
    }

    public function created_user()
    {
        return $this->hasOne(User::class,'id','created_users_id');
    }

    public function register_event()
    {
        return $this->hasOneThrough(
            RegisterEvent::class,
            RegisterTask::class,
            'id',//key RegisterTask
            'register_tasks_id',//foreign key RegisterEvent
            'register_tasks_id',//foreign key Verification
            'id' // Key RegisterEvent
        );
    }

    public function scopeAddClientDocument($query)
    {
        return $query->join('client_documents', 'client_documents.id','verifications.client_documents_id')
                     ->join('stage_tasks', 'stage_tasks.id','client_documents.stage_tasks_id')
                     ->join('documents','documents.id','client_documents.documents_id');
    }
}
