<?php

namespace App\Models;

use App\Models\MiProveedor\Cl1Clients;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



/**
 * App\Models\Client
 *
 * @property int $id
 * @property string $name Nombre del cliente
 * @property string $phone Teléfono del cliente
 * @property string $email Correo del cliente
 * @property string $contact_person Nombre de la persona de contacto
 * @property int $countries_id País del cliente
 * @property string $acronym Acrónimo de 3 digitos para la cración de solicitudes unicas
 * @property int $created_users_id
 * @property int|null $mp_clients_id Referencia tabla cl1_clients.cl1_id  Mi Proveedor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Country|null $country
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientDocument[] $documents
 * @property-read int|null $documents_count
 * @property-read Cl1Clients|null $mp_client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceList[] $price_list
 * @property-read int|null $price_list_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientTask[] $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\ClientFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Query\Builder|Client onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAcronym($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCountriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereMpClientsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Client withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Client withoutTrashed()
 * @mixin \Eloquent
 * @property int $interval_gd Intervalo de diás para los seguimientos en gestión documental
 * @property int $interval_sub Intervalo de diás para los seguimientos en subsanación
 * @property int $days_waiting Máximo días de espera para que el cliente gestione las solicitudes escaladas
 * @property string $review Revisión E:Estandar / I:Intensiva
 * @property int $analyst_time Tiempo que puede tardar un analista cerrando una tarea antes de que se le notifique al propio analista
 * @property int $coordinator_time Tiempo que puede tardar un analista cerrando una tarea antes de que se le notifique al coordinador
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAnalystTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCoordinatorTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDaysWaiting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereIntervalGd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereIntervalSub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereReview($value)
 */
class Client extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'contact_person',
        'created_users_id',
        'acronym',
        'interval_gd',
        'interval_sub',
        'days_waiting',
        'analyst_time',
        'coordinator_time',
        'mp_clients_id',
    ];

    public function users() {
        return $this->hasMany(User::class, 'clients_id', 'id');
    }

    public function countries() {
        return $this->hasMany(ClientsCountry::class, 'clients_id', 'id');
    }

    public function documents() {
        return $this->hasMany(ClientDocument::class, 'clients_id', 'id');
    }

    public function price_list()
    {
        return $this->hasMany(PriceList::class, 'clients_id', 'id');
    }

    public function tasks() {
        return $this->hasMany(ClientTask::class, 'clients_id', 'id');
    }

    public function mp_client()
    {
        return $this->hasOne(Cl1Clients::class, 'cl1_id', 'mp_clients_id');
    }
}
