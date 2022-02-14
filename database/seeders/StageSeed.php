<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;


class StageSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        truncate('stages');

        Stage::insert([
            [
                'name'             => 'Solicitud de Proveedores',
                'order'            => 1,
                'percentage'       => 10,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Análisis',
                'order'            => 1,
                'percentage'       => 5,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Comercial',
                'order'            => 1,
                'percentage'       => 20,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Gestión documental',
                'order'            => 1,
                'percentage'       => 20,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Verificación',
                'order'            => 1,
                'percentage'       => 20,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Subsanación',
                'order'            => 1,
                'percentage'       => 15,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Calidad',
                'order'            => 1,
                'percentage'       => 10,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]
        ]);
    }
}
