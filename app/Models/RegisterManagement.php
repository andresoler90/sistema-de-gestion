<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\RegisterManagement
 *
 * @property int $id
 * @property int $registers_id Id de la solicitud
 * @property int $stages_id Id de la etapa
 * @property string $management_type Tipo de gestión T:Teléfono / C:Correo / A:Ambos
 * @property string|null $observations Resultado de la verificación
 * @property string $decision decisión SN: Seguimiento nuevo / ES:Enviar soporte al proveedor / CS:Cancelar solicitud
 * @property string $status Estado A: Abierto / C:Cerrado
 * @property int $created_users_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement newQuery()
 * @method static \Illuminate\Database\Query\Builder|RegisterManagement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereDecision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereManagementType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereObservations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereRegistersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereStagesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterManagement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RegisterManagement withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RegisterManagement withoutTrashed()
 * @mixin \Eloquent
 */
class RegisterManagement extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $table = 'register_managements';
    protected $guarded = [];
}
