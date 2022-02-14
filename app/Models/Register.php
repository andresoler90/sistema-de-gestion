<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Register
 *
 * @property int $id
 * @property string $code Código de la soliciud
 * @property int $states_id Estado de la solicitud
 * @property string $register_type Tipo de registro L: Liviano / I:Integral
 * @property int $countries_id País del proveedor a solicitar
 * @property string $identification_type Tipo de identificación
 * @property string $identification_number Número de identificación
 * @property string|null $check_digit Dígito de verificación, solo para Colombia
 * @property string $business_name Razón social
 * @property string $telephone_contact Teléfono de contacto
 * @property string $name_contact Nombre de contacto
 * @property string $email_contact Correo de contacto
 * @property string $register_assumed_by Usuario quien asume la solicitud C: Cliente / P: Proveedor
 * @property int $requesting_users_id Usuario solicitante
 * @property int $created_users_id Usuario quien creo la solicitud en el sistema
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\State|null $state
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Register newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Register newQuery()
 * @method static \Illuminate\Database\Query\Builder|Register onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Register query()
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereCheckDigit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereEmailContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereIdentificationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereIdentificationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereNameContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereProviderCountriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereRegisterAssumedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereRegisterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereRequestingUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereStatesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereTelephoneContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Register withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Register withoutTrashed()
 * @mixin \Eloquent
 * @property int $clients_id Cliente al que pertenece el usuario
 * @property-read \App\Models\Client|null $client
 * @property-read \App\Models\User|null $creatorUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RegisterEvent[] $events
 * @property-read int|null $events_count
 * @property-read mixed $assumed_by_name
 * @property-read mixed $register_type_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RegisterTask[] $tasks
 * @property-read int|null $tasks_count
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereClientsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereCountriesId($value)
 * @property-read \App\Models\RegisterAdditionalField $additional_field
 * @property-read mixed $provider_type_name
 * @property-read mixed $stage
 * @property-read mixed $task_priority
 * @property-read \App\Models\RegisterSalesForce|null $salesforce
 * @property int $tasks_gd Máximo de tareas/seguimientos en la etapa de gestión documental
 * @property int $tasks_sub Máximo de tareas/seguimientos en la etapa de subsanación
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientDocument[] $client_documents
 * @property-read int|null $client_documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientTask[] $client_tasks
 * @property-read int|null $client_tasks_count
 * @property-read mixed $previous_stage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Verification[] $verifications
 * @property-read int|null $verifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Register addEvent()
 * @method static \Illuminate\Database\Eloquent\Builder|Register addclient()
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereTasksGd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Register whereTasksSub($value)
 */
class Register extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $fillable = [
        'code',
        'states_id',
        'register_type',
        'countries_id',
        'identification_type',
        'identification_number',
        'check_digit',
        'business_name',
        'telephone_contact',
        'name_contact',
        'email_contact',
        'register_assumed_by',
        'client_country_id',
        'clients_id',
        'requesting_users_id',
        'created_users_id',
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'requesting_users_id');
    }

    public function creatorUser()
    {
        return $this->hasOne(User::class, 'id', 'created_users_id');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'states_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'countries_id');
    }

    public function client_country()
    {
        return $this->hasOne(Country::class, 'id', 'client_country_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'clients_id');
    }

    public function additional_field()
    {
        return $this->belongsTo(RegisterAdditionalField::class, 'id', 'register_id');
    }

    public function salesforce()
    {
        return $this->hasOne(RegisterSalesForce::class, 'register_id', 'id');
    }

    public function getRegisterTypeNameAttribute()
    {
        switch ($this->register_type) {
            case 'L':
                return __('Liviano');
                break;

            case 'I':
                return __('Integral');
                break;
        }
    }

    public function getProviderTypeNameAttribute()
    {
        if($this->countries_id == $this->client->countries_id) {
            return __('Nacional');
        }
        return __('Internacional');

    }

    public function getAssumedByNameAttribute()
    {
        switch ($this->register_assumed_by) {
            case 'C':
                return __('Cliente');
                break;

            case 'P':
                return __('Proveedor');
                break;
        }
    }



    public function tasks()
    {
        return $this->hasMany(RegisterTask::class, 'registers_id', 'id');
    }

    public function events()
    {
        return $this->hasManyThrough(RegisterEvent::class,RegisterTask::class,'registers_id','register_tasks_id','id','id');
    }

    public function lastEvent()
    {
        return $this->events()->orderBy('register_events.created_at','DESC')->first();

    }



    public function getStageAttribute()
    {
        $register_event = RegisterEvent::where('registers_id',$this->id)
        ->orderBy('id','DESC')->first();
        $stage_task = StageTask::find($register_event->stage_tasks_id);
        $stage = Stage::find($stage_task->stages_id);
        return $stage;
    }

    public function getPreviousStageAttribute()
    {
        $current_stage = $this->stage;
        $previous_stage = $this->stage;

        $stages = Stage::all();

        foreach ($stages as $stage) {

            if($stage->id == $current_stage->id){
                break;
            }else{
                $previous_stage = $stage;
            }
        }

        // Si la solicitud se cierra correctamente se retorna la ultima etapa
        // para que el porcentage de avance sea del 100%
        if($this->states_id == 2){
            return $current_stage;
        }
        return $previous_stage;

    }


    public function getTaskPriorityAttribute()
    {
        $register_event = RegisterEvent::select('stage_tasks_id')
        ->where('registers_id',$this->id)
        ->orderBy('id','DESC')->first();

        $client_task = ClientTask::where([
            ['clients_id',$this->clients_id],
            ['stage_tasks_id',$register_event->stage_tasks_id],
        ])->first();

        $register_task = RegisterTask::where([
            ['client_tasks_id',$client_task->id],
            ['registers_id',$this->id],
        ])->first();

        switch ($register_task->priority) {
            case 'CPC':
                return __('Crítica por Calidad');
                break;
            case 'CRI':
                return __('Crítica');
                break;
            case 'ALT':
                return __('Alta');
                break;
            case 'MED':
                return __('Media');
                break;
            case 'BAJ':
                return __('Baja');
                break;
        }
    }

    public function client_documents()
    {
        return $this->hasMany(ClientDocument::class, 'clients_id', 'clients_id');
    }

    public function verifications()
    {
        return $this->hasMany(Verification::class, 'registers_id', 'id');

    }

    public function client_tasks()
    {
        return $this->hasMany(ClientTask::class, 'clients_id', 'clients_id');
    }

    public function scopeAddclient($query)
    {
        return $query->join('clients', 'clients.id', '=', 'registers.clients_id');

    }

    public function scopeAddEvent($query)
    {
        return $query->join('register_events','register_events.registers_id','registers.id');
    }

    public function scopeAddEventStage($query)
    {
        return $query->join('register_events','register_events.registers_id','registers.id')
                     ->join('stage_tasks', 'stage_tasks.id', '=', 'register_events.stage_tasks_id')
                     ->join('stages', 'stages.id', '=', 'stage_tasks.stages_id');

    }

    public function salesforce_contacts()
    {
        return $this->hasMany(RegisterContact::class, 'register_id', 'id');
    }

    public function salesforce_activities()
    {
        return $this->hasMany(RegisterSalesforceActivity::class, 'register_id', 'id');
    }
}
