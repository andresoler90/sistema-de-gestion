<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Act6Relcl1masters
 *
 * @property int $act6_id
 * @property int $act6_status
 * @property string $act6_createdDate
 * @property int $act1_id
 * @property int $cl1_id
 * @property int $lg1_creatorId
 * @property-read \App\Models\MiProveedor\Act1Masters|null $Act1
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters active()
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters addAct1Master()
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters query()
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters whereAct1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters whereAct6CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters whereAct6Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters whereAct6Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters whereCl1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters whereLg1CreatorId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Act6Relcl1masters filterByMaster($act1_id)
 */
class Act6Relcl1masters extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'act6_relcl1masters';

    public function scopeActive($query)
    {
        return $query->where('act6_status', 1);
    }

    public function Act1()
    {
        return $this->hasOne(Act1Masters::class, 'act1_id', 'act1_id');
    }

    public function scopeAddAct1Master($query)
    {
        return $query->join('act1_masters as act1', 'act1.act1_id', '=', 'act6_relcl1masters.act1_id');
    }

    public function scopeFilterByMaster($query, $act1_id)
    {
        if ($act1_id)
            return $query->where('act6_relcl1masters.act1_id', $act1_id);
    }
}
