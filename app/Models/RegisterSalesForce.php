<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RegisterSalesForce
 *
 * @property int $id
 * @property int $register_id
 * @property string $status Estado de ejecucion P => Pendiente E => Ejecutado
 * @property string|null $account ID de Cuenta SalesForce
 * @property string|null $contact ID de Contacto SalesForce
 * @property string|null $opportunity ID de Oprtunidad SalesForce
 * @property mixed|null $response_ws Response json de consumo ws
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce whereOpportunity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce whereResponseWs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterSalesForce whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $status_name
 * @property-read \App\Models\Register|null $register
 * @property-read \App\Models\Register|null $registerWithTrashed
 */
class RegisterSalesForce extends Model
{
    use HasFactory , Loggable;
    protected $table = "register_salesforce";

    public function register()
    {
        return $this->hasOne(Register::class, 'id', 'register_id');
    }
    public function registerWithTrashed()
    {
        return $this->hasOne(Register::class, 'id', 'register_id')->withTrashed();
    }
    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 'P':
                return __('Pendiente');
                break;
            case 'E':
                return __('Ejecutado');
                break;
        }
    }
    public function status()
    {
        switch ($this->status) {
            case 'P':
                return "<span class='badge badge-warning'>".__('Pendiente')."</span>";
                break;
            case 'E':
                return "<span class='badge badge-success'>".__('Ejecutado')."</span>";
                break;
        }
    }

    public function statusSalesforce()
    {
        if ($this->response_ws) {
            $stageName = json_decode($this->response_ws)->StageName;
            switch ($stageName) {
                case 'En ejecución y facturado. Oppty ganada':
                    return "<span class='badge badge-success'>" . $stageName . "</span>";
                    break;
                case 'Oppty perdida':
                    return "<span class='badge badge-danger'>" . $stageName . "</span>";
                    break;
                default:
                    return "<span class='badge badge-info'>" . $stageName . "</span>";
            }
        } else {
            return "<span class='badge badge-secondary'>" . __('Pendiente consultar') . "</span>";
        }
    }

    public function dateExecute()
    {
        if ($this->response_ws)
            return $this->updated_at;
        else
            return __('Sin ejecutar');
    }


}
