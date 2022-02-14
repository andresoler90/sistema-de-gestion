<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        truncate('permissions');

        Permission::insert([
            [
                'name'             => 'request_provider',
                'label'            => 'Solicitud de proveedores',
                'description'      => 'Este permiso le permite al usuario realizar solicitudes',
                'created_users_id' => '1',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'management_report',
                'label'            => 'Reporte de Gestión',
                'description'      => 'Este permiso le permite al usuario ver el reporte de Gestión',
                'created_users_id' => '1',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'ans_report',
                'label'            => 'Reporte ANS',
                'description'      => 'Este permiso le permite al usuario ver el reporte de ANS',
                'created_users_id' => '1',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'dashboard_report',
                'label'            => 'Reporte Dashboard',
                'description'      => 'Este permiso le permite al usuario ver el reporte de Dashboard',
                'created_users_id' => '1',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'ticket_status_report',
                'label'            => 'Reporte Estado de tiquetes',
                'description'      => 'Este permiso le permite al usuario ver el reporte del Estado de los tiquetes',
                'created_users_id' => '1',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}
