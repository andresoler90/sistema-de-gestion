<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Use1Identificationtype
 *
 * @property int $use1_id
 * @property string $use1_acronym
 * @property string $use1_description
 * @property int $use1_status
 * @property int $loc3_id
 * @method static \Illuminate\Database\Eloquent\Builder|Use1Identificationtype newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Use1Identificationtype newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Use1Identificationtype query()
 * @method static \Illuminate\Database\Eloquent\Builder|Use1Identificationtype whereLoc3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Use1Identificationtype whereUse1Acronym($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Use1Identificationtype whereUse1Description($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Use1Identificationtype whereUse1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Use1Identificationtype whereUse1Status($value)
 * @mixin \Eloquent
 */
class Use1Identificationtype extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'use1_identificationtypes';
}
