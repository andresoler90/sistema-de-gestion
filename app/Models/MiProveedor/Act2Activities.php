<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Act2Activities
 *
 * @property int $act2_id
 * @property string $act2_name
 * @property string|null $act2_description
 * @property string|null $act2_code
 * @property int $act2_type
 * @property int $act2_status
 * @property string $act2_createdDate
 * @property int $lg1_creatorId
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lg1_updateid
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities query()
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereAct2Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereAct2CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereAct2Description($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereAct2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereAct2Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereAct2Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereAct2Type($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereLg1Updateid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act2Activities whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Act2Activities extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'act2_activities';

}
