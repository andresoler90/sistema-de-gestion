<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\PriceList
 *
 * @property int $id
 * @property string|null $name Nombre de la plantilla
 * @property int $countries_id País para el que aplica el precio
 * @property string $register_assumed_by Usuario quien asume la solicitud C: Cliente / P: Proveedor
 * @property int|null $clients_id Id del cliente al que pertenece el usuario
 * @property string $register_type Tipo de registro L: Liviano / I:Integral / B: Basico
 * @property string|null $provider_type Tipo de proveedor N: Nacional / I:Internacional / B: Basico
 * @property string $currency Tipo de moneda
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Client|null $client
 * @property-read \App\Models\Country|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList newQuery()
 * @method static \Illuminate\Database\Query\Builder|PriceList onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList query()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereClientsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereCountriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereProviderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereRegisterAssumedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereRegisterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|PriceList withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PriceList withoutTrashed()
 * @mixin \Eloquent
 */
class PriceList extends Model
{
    use HasFactory, SoftDeletes,Loggable;

    protected $guarded  = [];

    public function country() {
        return $this->hasOne(Country::class, 'id', 'countries_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'clients_id');
    }

}
