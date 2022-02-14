<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Act10Relationscl1
 *
 * @property int $relations_id
 * @property int|null $cl1_id Relacion Tabla cl1_clients
 * @property int|null $act10_id Relacion Tabla Act10_typeactivities
 * @property int|null $act10_status Estados: 1 Activo | 2 Inactivo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lg1_creator Relacion Tabla Lg1_users
 * @property int|null $lg1_update Relacion Tabla Lg1_users
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 active()
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 addAct10Type()
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 addAct11Relations()
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 filterByMaster($act1_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 query()
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 whereAct10Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 whereAct10Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 whereCl1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 whereLg1Creator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 whereLg1Update($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 whereRelationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Relationscl1 whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Act10Relationscl1 extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'act10_relationscl1';


    public function scopeActive($query)
    {
        return $query->where('act10_status', 1);
    }
    public function scopeFilterByMaster($query, $act1_id)
    {
        if ($act1_id)
            return $query->where('act1_id', $act1_id);
    }
    public function scopeAddAct10Type($query)
    {
        return $query->join('act10_typeactivities as act10_type', 'act10_type.act10_id', '=', 'act10_relationscl1.act10_id');
    }
    public function scopeAddAct11Relations($query)
    {
        return $query->join('act11_relationsact', 'act11_relationsact.act10_id', '=', 'act10_relationscl1.act10_id');
    }

}
