<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Act11Relationsact
 *
 * @property int $act11rel_id
 * @property int $act11_id
 * @property int $act1_id
 * @property int $act10_id
 * @property int $act11_status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int $lg1_creatorId
 * @property int $lg1_updateId
 * @property int $act3_id
 * @property int $act2_id
 * @property int $act2_idR RELACION CON ACTIVIDAD DE MAESTRA PAR
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact active()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact addAct11Category()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact addAct2Activity()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact addAct3Group()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact filterByCategory($act11_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact filterByGroup($act3_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact filterByMaster($act1_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact filterByType($act10_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereAct10Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereAct11Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereAct11Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereAct11relId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereAct1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereAct2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereAct2IdR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereAct3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereLg1UpdateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Relationsact whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Act11Relationsact extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'act11_relationsact';

    public function scopeActive($query)
    {
        return $query->where('act11_status', 1);
    }

    public function scopeFilterByType($query, $act10_id)
    {
        if ($act10_id)
            return $query->where('act10_id', $act10_id);
    }

    public function scopeFilterByMaster($query, $act1_id)
    {
        if ($act1_id)
            return $query->where('act1_id', $act1_id);
    }

    public function scopeFilterByCategory($query, $act11_id)
    {
        if ($act11_id)
            return $query->where('act11_id', $act11_id);
    }

    public function scopeFilterByGroup($query, $act3_id)
    {
        if ($act3_id)
            return $query->where('act3_id', $act3_id);
    }

    public function scopeAddAct11Category($query)
    {
        return $query->join('act11_category', 'act11_category.act11_id', '=', 'act11_relationsact.act11_id');
    }

    public function scopeAddAct3Group($query)
    {
        return $query->join('act3_grouplists', 'act3_grouplists.act3_id', '=', 'act11_relationsact.act3_id');
    }

    public function scopeAddAct2Activity($query)
    {
        return $query->join('act2_activities', 'act2_activities.act2_id', '=', 'act11_relationsact.act2_id');
    }


}
