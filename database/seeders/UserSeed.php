<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'username' => 'administrador',
            'email' => 'admin@local.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345'),
            'phone' => '300 000 00 00',
            'clients_id' => null,
            'roles_id' => 1,
            'remember_token' => Str::random(10),
        ]);

        User::factory(50)->create();
    }
}
