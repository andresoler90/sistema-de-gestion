<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        truncate('roles');

        $roles = [
            'Administrador',
            'Analista',
            'Coordinador',
            'Gerente',
            'Cliente',
        ];

        foreach($roles as $value) {
            $rol = new Role();
            $rol->name = $value;
            $rol->save();
        }
    }
}
