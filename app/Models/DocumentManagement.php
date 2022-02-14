<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\DocumentManagement
 *
 * @property int $id
 * @property int $trackings_id Id del seguimiento
 * @property int $client_documents_id Id del documento
 * @property string $valid Valido S:Si / N:No
 * @property string|null $outcome Resultado de la verificación
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ClientDocument|null $clientDocument
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement newQuery()
 * @method static \Illuminate\Database\Query\Builder|DocumentManagement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement whereClientDocumentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement whereValid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement whereOutcome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement whereTrackingsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|DocumentManagement withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DocumentManagement withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentManagement addTracking()
 */
class DocumentManagement extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $table = 'document_managements';
    protected $guarded  = [];

    public function clientDocument()
    {
        return $this->hasOne(ClientDocument::class, 'id', 'client_documents_id')->with('document');
    }

    public function scopeAddTracking($query)
    {
        return $query->join('trackings','document_managements.trackings_id', '=', 'trackings.id');
    }
}
