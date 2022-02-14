<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Act3Grouplists
 *
 * @property int $act3_id
 * @property string $act3_name
 * @property string $act3_description
 * @property int $act3_status
 * @property string $act3_createDate
 * @property int $lg1_creatorId
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lg1_updateid
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists query()
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists whereAct3CreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists whereAct3Description($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists whereAct3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists whereAct3Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists whereAct3Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists whereLg1Updateid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Act3Grouplists whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Act3Grouplists extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'act3_grouplists';
}
