<?php

namespace Database\Seeders;

use App\Models\ClientsCountry;
use Illuminate\Database\Seeder;

class ClientCountrySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        truncate('clients_countries');

        ClientsCountry::insert([
            [
                'clients_id'       => 1,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 2,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 3,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 4,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 5,
                'countries_id'     => 144, // Perú
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 6,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 7,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 8,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 9,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 9,
                'countries_id'     => 144, // Perú
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 10,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 11,
                'countries_id'     => 144, // Perú
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 12,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 13,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 14,
                'countries_id'     => 26, // Brasil
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 15,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 16,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 17,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 18,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 19,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 19,
                'countries_id'     => 40, // Chile
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 19,
                'countries_id'     => 121, // México
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 19,
                'countries_id'     => 144, // Perú
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 20,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 20,
                'countries_id'     => 144, // Perú
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 21,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 22,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 23,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 24,
                'countries_id'     => 40, // Chile
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 25,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 26,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 26,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 28,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 29,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 30,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 31,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 32,
                'countries_id'     => 144, // Perú
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 33,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 34,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 35,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 36,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 37,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 38,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 39,
                'countries_id'     => 144, // Perú
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 40,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 41,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 42,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 43,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 44,
                'countries_id'     => 144, // Perú
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 45,
                'countries_id'     => 144, // Perú
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 46,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 47,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 48,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'clients_id'       => 49,
                'countries_id'     => 43, // Colombia
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}
