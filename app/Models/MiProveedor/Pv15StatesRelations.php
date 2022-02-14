<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\MiProveedor\Pv15StatesRelations
 *
 * @property int $pv15_id
 * @property string|null $pv15_start_date
 * @property string|null $pv15_end_date
 * @property string|null $pv15_observation
 * @property string|null $pv15_modificationDate
 * @property int $pv15_status
 * @property string $pv15_createdDate
 * @property int $pv1_id
 * @property int|null $conf5_id
 * @property int|null $conf4_id
 * @property int $lg1_creatorId
 * @property int|null $cl1_id
 * @property string|null $pv15_codigointerno1
 * @property string|null $pv15_codigointerno2
 * @property string|null $pv15_nombreinterno1
 * @property string|null $pv15_nombreinterno2
 * @property int|null $pv32_id
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int|null $lg1_updateId
 * @property string|null $pv15_acta
 * @property int|null $pv33_id
 * @property int|null $pv34_id
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations active()
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations client($cl1_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations conf4Accepted()
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations whereCl1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations whereConf4Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations whereConf5Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations whereLg1UpdateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15Acta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15Codigointerno1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15Codigointerno2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15EndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15ModificationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15Nombreinterno1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15Nombreinterno2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15Observation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15StartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv15Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv32Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv33Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations wherePv34Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pv15StatesRelations whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pv15StatesRelations extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'pv15_statesrelations';

    public function scopeActive($query)
    {
        return $query->where('pv15_status', 1);
    }

    public function scopeClient($query, $cl1_id)
    {
        return $query->where('cl1_id', $cl1_id);
    }

    public function scopeConf4Accepted($query)
    {
        return $query->whereIn('conf4_id', [1, 50]);
    }

    public function paymentsByClient($cl1_id)
    {
        return $this->hasMany(Paym1ProviderPayments::class, 'pv1_id', 'pv1_id')->Client($cl1_id)->Active()->OrderPaymentDesc();
    }

    public function scopePaymentPartial($query)
    {
        return $query->where('conf4_id', 21);
    }

    public function scopePaymentIntegral($query)
    {
        return $query->where('conf4_id', 38);
    }
}
