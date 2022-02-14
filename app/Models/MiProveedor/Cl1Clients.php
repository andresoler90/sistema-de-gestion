<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Cl1Clients
 *
 * @property int $cl1_id
 * @property string $cl1_name
 * @property string $cl1_identification
 * @property int $cl1_status
 * @property int|null $cl1_allProviders
 * @property int $lg1_creatorId
 * @property string $cl1_createdDate
 * @property int|null $cl5_id
 * @property int $cl1_moduleStatus
 * @property int|null $cl1_vlrtrm_preca
 * @property int|null $cl1_priority
 * @property int $cl1_referredBy VER EN EL NUEVO PRE REGISTRO ESTANDAR LISTA REFERIDO POR
 * @property int $cl1_providerSelected Cliente habilitado para proveedor 1 Activo / 0 Inactivo
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients active()
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1AllProviders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1Identification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1ModuleStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1Priority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1ProviderSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1ReferredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl1VlrtrmPreca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereCl5Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cl1Clients whereLg1CreatorId($value)
 * @mixin \Eloquent
 */
class Cl1Clients extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'cl1_clients';

    public function scopeActive($query)
    {
        return $query->where('cl1_status', 1);
    }

}
