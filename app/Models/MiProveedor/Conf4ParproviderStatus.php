<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Conf4ParproviderStatus
 *
 * @property int $conf4_id
 * @property string $conf4_name
 * @property int $conf4_status
 * @property int $conf4_config_status Estado para la configuracion de estados del sistema,Estados del cliente,(Estados cliente)
 * @property string $conf4_createdDate
 * @property int $cl1_id
 * @property int $lg1_creatorid
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus whereCl1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus whereConf4ConfigStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus whereConf4CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus whereConf4Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus whereConf4Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus whereConf4Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conf4ParproviderStatus whereLg1Creatorid($value)
 * @mixin \Eloquent
 */
class Conf4ParproviderStatus extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'conf4_parproviderstatus';



}
