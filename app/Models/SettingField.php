<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SettingCamp
 *
 * @property int $id
 * @property string $name Nombre del campo
 * @property string|null $description Descripcion del campo
 * @property int $default_value Valor por defecto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField newQuery()
 * @method static \Illuminate\Database\Query\Builder|SettingField onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField query()
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SettingField withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SettingField withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|SettingField whereSlug($value)
 */
class SettingField extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'default_value'
    ];

    public function switchStatus(): string
    {
        switch ($this->default_value) {
            case 0:
                $checked = '';
                $name = __('Deshabilitado');
                break;
            case  1:
                $checked = 'checked';
                $name = __('Habilitado');
                break;
        }
        return '
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input"
                           id="default_value" name="default_value" value="'.$this->default_value.'" ' . $checked . ' disabled>
                    <label class="custom-control-label" for="default_value" id="statusSwitch">' . $name . '
                    </label>
                </div>
                ';

    }
    public function nameStatus(): string
    {
        switch ($this->default_value) {
            case 0:
                return __('Deshabilitado');
                break;
            case  1:
                return __('Habilitado');
                break;
        }

    }
}
