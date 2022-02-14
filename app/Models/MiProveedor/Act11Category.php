<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Act11Category
 *
 * @property int $act11_id
 * @property string $act11_name Nombre de categori de actividad
 * @property string|null $act11_description Descripcion de categori de actividad
 * @property string|null $act11_createdDate
 * @property int|null $lg1_creatorId
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lg1_updateid
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category whereAct11CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category whereAct11Description($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category whereAct11Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category whereAct11Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category whereLg1Updateid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act11Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Act11Category extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'act11_category';

}
