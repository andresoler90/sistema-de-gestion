<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Act10Typeactivities
 *
 * @property int $act10_id
 * @property string $act10_name Nombre de tipo de actividad
 * @property string $act10_description Descripcion de tipo de actividad
 * @property string $act10_createdDate
 * @property int|null $lg1_creatorId
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lg1_updateid
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities query()
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities whereAct10CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities whereAct10Description($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities whereAct10Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities whereAct10Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities whereLg1Updateid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act10Typeactivities whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Act10Typeactivities extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'act10_typeactivities';

}
