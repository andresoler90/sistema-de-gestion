<?php

namespace App\Models;

use App\Models\MiProveedor\Act10Typeactivities;
use App\Models\MiProveedor\Act11Category;
use App\Models\MiProveedor\Act1Masters;
use App\Models\MiProveedor\Act2Activities;
use App\Models\MiProveedor\Act3Grouplists;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RegisterAdditionalField
 *
 * @property int $id
 * @property int $register_id
 * @property int|null $act1_id
 * @property int|null $act2_id
 * @property int|null $act3_id
 * @property int|null $act10_id
 * @property int|null $act11_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereAct10Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereAct11Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereAct1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereAct2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereAct3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $code_activity Código actividad
 * @property string|null $code_intern Código Interno
 * @property int|null $experience_verify Experiencia Verificada
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereCodeActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereCodeIntern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterAdditionalField whereExperienceVerify($value)
 * @property-read Act2Activities|null $activity
 * @property-read Act11Category|null $category
 * @property-read Act3Grouplists|null $group
 * @property-read Act1Masters|null $master
 * @property-read Act10Typeactivities|null $type
 */
class RegisterAdditionalField extends Model
{
    use HasFactory;

    public function master()
    {
        return $this->hasOne(Act1Masters::class, 'act1_id', 'act1_id');
    }

    public function type()
    {
        return $this->hasOne(Act10Typeactivities::class, 'act10_id', 'act10_id');
    }

    public function category()
    {
        return $this->hasOne(Act11Category::class, 'act11_id', 'act11_id');
    }

    public function group()
    {
        return $this->hasOne(Act3Grouplists::class, 'act3_id', 'act3_id');
    }

    public function activity()
    {
        return $this->hasOne(Act2Activities::class, 'act2_id', 'act2_id');
    }

    public function master_name()
    {
        if($this->master()->first()!=null)
            return $this->master()->first()->act1_name;
        return null;
    }

    public function type_name()
    {
        if($this->type()->first()!=null)
            return $this->type()->first()->act10_name;
        return null;
    }

    public function category_name()
    {
        if($this->category()->first()!=null)
            return $this->category()->first()->act11_name;
        return null;
    }

    public function group_name()
    {
        if($this->group()->first()!=null)
            return $this->group()->first()->act3_name;
        return null;
    }

    public function activity_name()
    {
        if($this->activity()->first()!=null)
            return $this->activity()->first()->act2_name;
        return null;
    }

    public function verify_status()
    {
        switch ($this->experience_verify){
            case 0:
                return __('No');
                break;
            case  1:
                return __('Si');
                break;
        }
    }

}
