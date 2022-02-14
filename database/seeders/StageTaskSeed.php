<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StageTask;

class StageTaskSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        truncate('stage_tasks');

        StageTask::insert([
            // Etapa 1
            [
                'name'             => 'sp_task_1',
                'label'             => 'Solicitud de proveedores',
                'stages_id'        => 1,
                'visible'          => 'N',
                'estimated_time'   => 1.5,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Etapa 2
            [
                'name'             => 'an_task_1',
                'label'             => 'Análisis',
                'stages_id'        => 2,
                'visible'          => 'N',
                'estimated_time'   => 30.5,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Etapa 3
            [
                'name'             => 'co_task_1',
                'label'             => 'Comercial',
                'stages_id'        => 3,
                'visible'          => 'S',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Etapa 4
            [
                'name'             => 'gd_task_1',
                'label'             => 'Gestión documental',
                'stages_id'        => 4,
                'visible'          => 'N',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Etapa 5
            [
                'name'             => 've_task_1',
                'label'             => 'Verificación básica',
                'stages_id'        => 5,
                'visible'          => 'S',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 've_task_2',
                'label'             => 'Verificación de Experiencias',
                'stages_id'        => 5,
                'visible'          => 'S',
                'estimated_time'   => 2,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 've_task_3',
                'label'             => 'Verificación financiera',
                'stages_id'        => 5,
                'visible'          => 'S',
                'estimated_time'   => 3,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 've_task_4',
                'label'             => 'Verificación de documentos del cliente',
                'stages_id'        => 5,
                'visible'          => 'S',
                'estimated_time'   => 3,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 've_task_5',
                'label'            => 'Verificación sin éxito - Se envía correo al proveedor',
                'stages_id'        => 5,
                'visible'          => 'N',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Etapa 6
            [
                'name'             => 'su_task_1',
                'label'             => 'Subsanación básica',
                'stages_id'        => 6,
                'visible'          => 'N',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'su_task_2',
                'label'             => 'Subsanación de experiencias',
                'stages_id'        => 6,
                'visible'          => 'N',
                'estimated_time'   => 2,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'su_task_3',
                'label'             => 'Subsanación financiera',
                'stages_id'        => 6,
                'visible'          => 'N',
                'estimated_time'   => 3,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'su_task_4',
                'label'             => 'Subsanación de documentos del cliente',
                'stages_id'        => 6,
                'visible'          => 'N',
                'estimated_time'   => 3,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'su_task_5',
                'label'             => 'Subsanación sin éxito - Se envía correo al proveedor',
                'stages_id'        => 6,
                'visible'          => 'N',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Etapa 7
            [
                'name'             => 'ca_task_1',
                'label'             => 'Calidad',
                'stages_id'        => 7,
                'visible'          => 'N',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'ca_task_2',
                'label'             => 'Calidad - Subsanación básica',
                'stages_id'        => 7,
                'visible'          => 'N',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'ca_task_3',
                'label'             => 'Calidad - Subsanación de experiencias',
                'stages_id'        => 7,
                'visible'          => 'N',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'ca_task_4',
                'label'             => 'Calidad - Subsanación financiera',
                'stages_id'        => 7,
                'visible'          => 'N',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'ca_task_5',
                'label'             => 'Calidad - Subsanación de documentos del cliente',
                'stages_id'        => 7,
                'visible'          => 'N',
                'estimated_time'   => 1,
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}
