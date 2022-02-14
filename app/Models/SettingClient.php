<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SettingClient
 *
 * @property int $id
 * @property int $setting_camp_id
 * @property int $client_id
 * @property int $status Estado Activo/Inactivo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient newQuery()
 * @method static \Illuminate\Database\Query\Builder|SettingClient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient query()
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereSettingCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SettingClient withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SettingClient withoutTrashed()
 * @mixin \Eloquent
 * @property int|null $created_user_id Usuario Creador
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereCreatedUserId($value)
 * @property int|null $updated_user_id Usuario modificador
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereUpdatedUserId($value)
 * @property int $setting_field_id
 * @property-read \App\Models\SettingField|null $camp
 * @property-read \App\Models\Client|null $client
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient whereSettingFieldId($value)
 * @property-read \App\Models\SettingField|null $field
 * @method static \Illuminate\Database\Eloquent\Builder|SettingClient active()
 */
class SettingClient extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $fillable = [
        'setting_field_id',
        'client_id',
        'status',
    ];

    public function field()
    {
        return $this->hasOne(SettingField::class, 'id', 'setting_field_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');

    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function status($edit): string
    {
        switch ($this->status) {
            case 0:
                $checked = '';
                $name = __('Inactivo');
                break;
            case  1:
                $checked = 'checked';
                $name = __('Activo');
                break;
        }
        $disabled = $edit ? null : 'disabled';
        return '
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input"
                           id="setting_client_' . $this->id . '"
                           name="setting_client_[]"
                           value="' . $this->status . '"
                           ' . $checked . '
                           ' . $disabled . '
                           onclick = "validateStatus(' . $this->id . ')"
                           >
                    <label class="custom-control-label" for="setting_client_' . $this->id . '" id="status_' . $this->id . '">' . $name . '
                    </label>
                </div>
                ';

    }
}
