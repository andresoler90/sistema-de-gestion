<?php

namespace App\Models\MiProveedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MiProveedor\Contact
 *
 * @property int $pv17_id
 * @property string $pv17_identification
 * @property string $pv17_name
 * @property int $pv17_representativetype
 * @property string $pv17_phone
 * @property string|null $pv17_negotiationcapacity
 * @property int|null $pv17_limitofnegotiation
 * @property string|null $pv17_email
 * @property string|null $pv17_attached1 Anexar DOC Reporte legal
 * @property string|null $pv17_attached1_PATH
 * @property string|null $pv17_documentexpeditiondoc
 * @property int $pv17_status
 * @property int $pv17_modulestatus 1 creado/afectado por Admin | 2 Creado por Proveedor | 3 Afectado por proveedor
 * @property string $pv17_createddate
 * @property int $pv1_id
 * @property int $use1_id
 * @property int $loc3_id
 * @property int $loc2_id
 * @property int $loc1_id
 * @property string|null $pv17_cityexpedition
 * @property int|null $lg1_creatorid
 * @property string|null $pv17_evrdSession
 * @property string|null $pv17_attached_temp
 * @property string|null $pv17_expirateDate
 * @property string|null $pv17_cellPhone
 * @property string|null $pv17_cellPhoneInd
 * @property string|null $pv17_cellPhoneExt
 * @property string|null $pv17_phoneInd
 * @property string|null $pv17_phoneExt
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lg1_updatedId
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLg1Creatorid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLg1UpdatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLoc1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLoc2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLoc3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Attached1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Attached1PATH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17AttachedTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17CellPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17CellPhoneExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17CellPhoneInd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Cityexpedition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Createddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Documentexpeditiondoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Email($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17EvrdSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17ExpirateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Identification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Limitofnegotiation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Modulestatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Negotiationcapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Phone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17PhoneExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17PhoneInd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Representativetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv17Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePv1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUse1Id($value)
 * @mixin \Eloquent
 * @property-read \App\Models\MiProveedor\Loc3Country|null $loc3
 */
class Contact extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'pv17_contacts';

    public function loc3()
    {
        return $this->hasOne(Loc3Country::class, 'loc3_id', 'loc3_id');
    }
}
