<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\RegisterSalesforceActivity
 *
 * @property int $id
 * @property int $register_id Registro
 * @property string|null $account Id Cuenta en SalesForce
 * @property string|null $opportunity Id oportunidad en SalesForce
 * @property string|null $activity Id actividad/tarea en SalesForce
 * @property mixed|null $data_json Data consultada de API - Salesforce
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Register|null $register
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity newQuery()
 * @method static \Illuminate\Database\Query\Builder|RegisterSalesforceActivity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity whereActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity whereOpportunity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesforceActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RegisterSalesforceActivity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RegisterSalesforceActivity withoutTrashed()
 * @mixin \Eloquent
 */
class RegisterSalesforceActivity extends Model
{
    use HasFactory, Loggable, SoftDeletes;
    protected $guarded = [];

    public function register()
    {
        return $this->hasOne(Register::class, 'id', 'register_id');
    }
}
