<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Loc2State
 *
 * @property int $loc2_id
 * @property string $loc2_name
 * @property string|null $loc2_name_BR
 * @property int $loc2_status
 * @property string $loc2_createdDate
 * @property int $lg1_creatorId
 * @property int $loc3_id
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State whereLoc2CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State whereLoc2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State whereLoc2Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State whereLoc2NameBR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State whereLoc2Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc2State whereLoc3Id($value)
 * @mixin \Eloquent
 */
class Loc2State extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'loc2_states';

}
