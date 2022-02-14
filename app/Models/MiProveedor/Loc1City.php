<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Loc1City
 *
 * @property int $loc1_id
 * @property string $loc1_name
 * @property string|null $loc1_name_BR
 * @property int $loc1_status
 * @property string $loc1_createdDate
 * @property int $lg1_creatorId
 * @property int $loc2_id
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City whereLoc1CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City whereLoc1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City whereLoc1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City whereLoc1NameBR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City whereLoc1Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc1City whereLoc2Id($value)
 * @mixin \Eloquent
 */
class Loc1City extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'loc1_cities';
}
