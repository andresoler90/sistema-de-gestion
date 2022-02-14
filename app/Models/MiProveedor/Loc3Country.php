<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Loc3Country
 *
 * @property int $loc3_id
 * @property string $loc3_shortName
 * @property string $loc3_officialName
 * @property string|null $loc3_iso3
 * @property string|null $loc3_iso2
 * @property string|null $loc3_faostat
 * @property string|null $loc3_gaul
 * @property string|null $loc3_codDian
 * @property string|null $loc3_language
 * @property int $loc3_status
 * @property string $loc3_createdDate
 * @property int $lg1_creatorId
 * @property string|null $loc3_iso3166Num
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3CodDian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3Faostat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3Gaul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3Iso2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3Iso3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3Iso3166Num($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3Language($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3OfficialName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3ShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereLoc3Status($value)
 * @mixin \Eloquent
 * @property int|null $use6_idC
 * @method static \Illuminate\Database\Eloquent\Builder|Loc3Country whereUse6IdC($value)
 */
class Loc3Country extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'loc3_countries';
}
