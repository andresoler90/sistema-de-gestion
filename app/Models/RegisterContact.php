<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\RegisterContact
 *
 * @property int $id
 * @property int $register_id Registro
 * @property string|null $account_id Id Cuenta en SalesForce
 * @property string|null $contact_id Id Contacto en SalesForce
 * @property mixed|null $data_json Data consultada de API - Salesforce
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Register|null $register
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact newQuery()
 * @method static \Illuminate\Database\Query\Builder|RegisterContact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterContact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RegisterContact withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RegisterContact withoutTrashed()
 * @mixin \Eloquent
 */
class RegisterContact extends Model
{
    use HasFactory, Loggable, SoftDeletes;
    protected $guarded = [];

    public function register()
    {
        return $this->hasOne(Register::class,'id','register_id');
    }


}
