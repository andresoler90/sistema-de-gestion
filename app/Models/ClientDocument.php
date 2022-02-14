<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ClientDocument
 *
 * @property int $id
 * @property int $clients_id ID del cliente
 * @property int $documents_id ID del documento
 * @property string $register_type Tipo de registro L: Liviano / I:Integral
 * @property string $document_type Tipo de documento OP: Opcional / OB: obligatorio
 * @property string|null $validity Vigencia
 * @property string|null $commentary Comentario
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $provider_type Tipo de proveedor N: Nacional / I: Internacional
 * @property-read \App\Models\Document|null $document
 * @property-read mixed $document_type_name
 * @property-read mixed $provider_type_name
 * @property-read mixed $register_type_name
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument newQuery()
 * @method static \Illuminate\Database\Query\Builder|ClientDocument onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereClientsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereCommentary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereDocumentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereProviderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereRegisterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereValidity($value)
 * @method static \Illuminate\Database\Query\Builder|ClientDocument withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ClientDocument withoutTrashed()
 * @mixin \Eloquent
 * @property int $stage_tasks_id ID de la tarea
 * @property-read \App\Models\StageTask|null $stage_task
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument documentType($documentType)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument typeOptional()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument typeRequired()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDocument whereStageTasksId($value)
 */
class ClientDocument extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $guarded  = [];

    public function document()
    {
        return $this->hasOne(Document::class, 'id', 'documents_id');
    }

    public function getDocumentTypeNameAttribute()
    {
        switch ($this->document_type) {
            case 'OP':
                return __('Opcional');
                break;

            case 'OB':
                return __('Obligatorio');
                break;
        }
    }

    public function getRegisterTypeNameAttribute()
    {
        switch ($this->register_type) {
            case 'L':
                return __('Liviano');
                break;

            case 'I':
                return __('Integral');
                break;
        }
    }

    public function getProviderTypeNameAttribute()
    {
        switch ($this->provider_type) {
            case 'N':
                return __('Nacional');
                break;

            case 'I':
                return __('Internacional');
                break;
        }
    }

    public function scopeTypeRequired($query)
    {
        return $query->where('document_type', 'OB');
    }

    public function scopeTypeOptional($query)
    {
        return $query->where('document_type', 'OP');
    }

    public function scopeDocumentType($query, $documentType)
    {
        if ($documentType != 'ALL')
            return $query->where('document_type',$documentType);
    }

    public function stage_task()
    {
        return $this->hasOne(StageTask::class,'stage_tasks_id','id');
    }
}
