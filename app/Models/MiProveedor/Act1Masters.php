<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Act1Masters
 *
 * @property int $act1_id
 * @property string $act1_name
 * @property string|null $act1_description
 * @property int $act1_status
 * @property int $lg1_creatorId
 * @property string $act1_createdDate
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lg1_updateid
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters query()
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters whereAct1CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters whereAct1Description($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters whereAct1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters whereAct1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters whereAct1Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters whereLg1Updateid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act1Masters whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Act1Masters extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'act1_masters';

}
