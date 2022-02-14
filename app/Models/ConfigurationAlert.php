<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigurationAlert extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $fillable = [
        "name",
        "command",
        "clients_id",
        "periodicity",
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'clients_id');
    }

    public function getLabelPeriodicityAttribute()
    {
        switch ($this->periodicity) {
            case 'daily':
                $label = __('Diario');
                break;

            case 'weekly':
                $label = __('Semanal');
                break;

            case 'monthly':
                $label = __('Mensual');
                break;

            default:
                $label = '';
        }
        return $label;
    }
}
