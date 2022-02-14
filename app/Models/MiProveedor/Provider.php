<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Provider
 *
 * @property int $pv1_id
 * @property string $pv1_identification
 * @property string|null $pv1_dv
 * @property string $pv1_providerName
 * @property string|null $pv1_commercialName
 * @property string|null $pv1_shortName
 * @property string|null $pv1_oracleNumber Numero oracle buscador 09-04-2019
 * @property int $pv1_status
 * @property string $pv1_createdDate
 * @property int|null $lg1_creatorId
 * @property int|null $use1_id
 * @property int $loc1_id
 * @property int|null $loc2_id
 * @property int|null $loc3_id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_at_nit
 * @property int|null $lg1_updatedId
 * @property int|null $lg1_updatedIdNit
 * @property-read \App\Models\MiProveedor\Contact|null $contact
 * @method static \Illuminate\Database\Eloquent\Builder|Provider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider query()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereLg1CreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereLg1UpdatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereLg1UpdatedIdNit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereLoc1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereLoc2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereLoc3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePv1CommercialName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePv1CreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePv1Dv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePv1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePv1Identification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePv1OracleNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePv1ProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePv1ShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider wherePv1Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereUpdatedAtNit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereUse1Id($value)
 * @mixin \Eloquent
 * @property-read \App\Models\MiProveedor\Loc1City|null $loc1
 * @property-read \App\Models\MiProveedor\Loc2State|null $loc2
 * @property-read \App\Models\MiProveedor\Loc3Country|null $loc3
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MiProveedor\Paym1ProviderPayments[] $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MiProveedor\Pv15StatesRelations[] $pv15
 * @property-read int|null $pv15_count
 * @property-read \App\Models\MiProveedor\Use1Identificationtype|null $use1
 */
class Provider extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'pv1_providers';

    public function contact()
    {
        return $this->hasOne(Contact::class, 'pv1_id', 'pv1_id');
    }

    public function payments()
    {
        return $this->hasMany(Paym1ProviderPayments::class, 'pv1_id', 'pv1_id');
    }

    public function paymentsByClient($cl1_id)
    {
        return $this->hasMany(Paym1ProviderPayments::class, 'pv1_id', 'pv1_id')->Client($cl1_id)->Active()->OrderPaymentDesc();
    }

    public function pv15()
    {
        return $this->hasMany(Pv15StatesRelations::class, 'pv1_id', 'pv1_id');
    }
    public function pv15ClientAccepted($cl1_id)
    {
        return $this->hasMany(Pv15StatesRelations::class, 'pv1_id', 'pv1_id')->Client($cl1_id)->Active()->Conf4Accepted()->first();
    }

    public function loc1()
    {
        return $this->hasOne(Loc1City::class, 'loc1_id', 'loc1_id');
    }

    public function loc2()
    {
        return $this->hasOne(Loc2State::class, 'loc2_id', 'loc2_id');
    }

    public function loc3()
    {
        return $this->hasOne(Loc3Country::class, 'loc3_id', 'loc3_id');
    }

    public function use1()
    {
        return $this->hasOne(Use1Identificationtype::class, 'use1_id', 'use1_id');
    }
}

