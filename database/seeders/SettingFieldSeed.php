<?php

namespace Database\Seeders;

use App\Models\SettingField;
use Illuminate\Database\Seeder;

class SettingFieldSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $field = new SettingField();
        $field->name = 'Actividades';
        $field->description = 'Permite visualizar campo adicional de Actividades MiProveedor';
        $field->slug = 'view_activities';
        $field->default_value = 1;
        $field->save();

    }
}
