<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\QualityDocumentReview
 *
 * @property int $id
 * @property int $registers_id ID de la solicitud
 * @property int $register_tasks_id ID de la tarea
 * @property int $client_documents_id ID del documento
 * @property string $satisfy Cumple, S:Si / N:No
 * @property string|null $fingering_review Revisión de digitación, S:Si / N:No
 * @property string|null $comments Comentarios
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ClientDocument|null $client_document
 * @property-read \App\Models\RegisterTask|null $register_task
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview addClientDocuments()
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview addStageTasks()
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview newQuery()
 * @method static \Illuminate\Database\Query\Builder|QualityDocumentReview onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereClientDocumentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereFingeringReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereRegisterTasksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereRegistersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereSatisfy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QualityDocumentReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|QualityDocumentReview withTrashed()
 * @method static \Illuminate\Database\Query\Builder|QualityDocumentReview withoutTrashed()
 * @mixin \Eloquent
 */
class QualityDocumentReview extends Model
{
    use HasFactory, SoftDeletes, Loggable;
    protected $table = "quality_document_review";

    protected $dates = ['created_at'];

    public function register_task()
    {
        return $this->hasOne(RegisterTask::class, 'register_tasks_id', 'id');
    }

    public function client_document()
    {
        return $this->hasOne(ClientDocument::class, 'client_documents_id', 'id');
    }

    public function created_user()
    {
        return $this->hasOne(User::class,'id','created_users_id');
    }

    public function scopeAddClientDocuments($query)
    {
        return $query->join('client_documents','client_documents.id','=','quality_document_review.client_documents_id');
    }

    public function scopeAddStageTasks($query)
    {
        return $query->join('stage_tasks','stage_tasks.id','=','client_documents.stage_tasks_id');
    }

    public function scopeAddClientDocument($query)
    {
        return $query->join('client_documents', 'client_documents.id','quality_document_review.client_documents_id')
                     ->join('documents','documents.id','client_documents.documents_id');
    }
}
