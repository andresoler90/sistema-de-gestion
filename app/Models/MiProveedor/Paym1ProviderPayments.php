<?php

namespace App\Models\MiProveedor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Paym1ProviderPayments
 *
 * @property int $paym1_id
 * @property string $paym1_paymentDate
 * @property int $paym1_baseValue
 * @property int $paym1_paymentValue
 * @property int $paym1_methodpayment
 * @property int $paym1_concept
 * @property string $paym1_year
 * @property string $paym1_contactName
 * @property string $paym1_contactEmail
 * @property string $paym1_address
 * @property string $paym1_phone
 * @property string $paym1_attached
 * @property string|null $paym1_certificate
 * @property string|null $paym1_description
 * @property int $paym1_modulestatus 2 ==> visible para proveedor y admin | 1 ==> visibles para todo el sistema
 * @property int $paym1_status
 * @property string $paym1_createddate
 * @property int $lg1_creatorId
 * @property int $pv1_id
 * @property int|null $loc3_id
 * @property int|null $loc2_id
 * @property int|null $loc1_id
 * @property int $cl1_id
 * @property int|null $lg1_userUpdate
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments active()
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments client($cl1_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments integral()
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments orderPaymentDesc()
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments partial()
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments query()
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments whereCl1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments whereLg1UserUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments whereLoc1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments whereLoc2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments whereLoc3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Address($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Attached($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1BaseValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Certificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Concept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1ContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1ContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Createddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Description($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Methodpayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Modulestatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1PaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1PaymentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Phone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePaym1Year($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments wherePv1Id($value)
 * @mixin \Eloquent
 * @property int|null $use6_id
 * @method static \Illuminate\Database\Eloquent\Builder|Paym1ProviderPayments whereUse6Id($value)
 */
class Paym1ProviderPayments extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'paym1_providerpayments';

    public function scopePartial($query)
    {
        return $query->whereIn('paym1_concept', [2, 3]);
    }

    public function scopeIntegral($query)
    {
        return $query->whereIn('paym1_concept', [1, 4]);
    }

    public function scopeActive($query)
    {
        return $query->where('paym1_status', 1);
    }

    public function scopeClient($query, $cl1_id)
    {
        return $query->where('cl1_id', $cl1_id);
    }

    public function scopeOrderPaymentDesc($query)
    {
        return $query->orderBy('paym1_year', 'DESC')->orderBy('paym1_paymentDate', 'DESC')->orderBy('paym1_createddate', 'DESC');
    }

    /**
     * @return string|null
     * @todo [1 ó 4 es integral] y [2 o 3  es parcial]
     */
    public function type()
    {
        switch ($this->paym1_concept) {
            case 1:
            case 4:
                $type = 'Integral';
                break;
            case 2:
            case 3:
                $type = 'Parcial';
                break;
            default:
                $type = null;
        }
        return $type;
    }
}
