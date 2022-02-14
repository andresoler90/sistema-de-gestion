<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Vacia las tablas que contienen seeders para evitar datos duplicados
        // al ejecutar: php artisan db:seed
        $this->truncate([
            'countries',
            'roles',
            'states',
            'users',
            'clients',
            'price_lists',
            'permissions',
            'stages',
            'stage_tasks',
            'client_tasks',
            'registers',
            'register_events',
            'register_tasks',
            'analyst_tasks',
            'documents',
            'client_documents',
            'setting_fields',
            'configurable_words',
        ]);

        $this->call([
            CountrySeed::class,   // Seeder independiente
            RoleSeed::class,      // Seeder independiente
            StateSeed::class,     // Seeder independiente
            UserSeed::class,      // Para crear usuarios se necesitan roles
            ClientSeed::class,    // Para crear clientes se necesita al menos un usuario y paises
        ]);

        // Justo después de haber creado usuarios y clientes se debe modificar los usuarios
        $this->modify_users();

        $this->call([
            PriceListSeed::class,     // Para crear lista de precios se necesitan clientes y paises
            PermissionSeed::class,    // Para crear permisos se necesita al menos un usuario
            StageSeed::class,         // Seeder independiente
            StageTaskSeed::class,     // Para crear tareas se necesitan etapas
            AnalystTaskSeed::class,   //
            ClientTaskSeed::class,    // Para crear tareas de clientes se necesita al menos un usuario, un cliente y una tarea
            RegisterSeed::class,      //
            RegisterTaskSeed::class,  //
            RegisterEventSeed::class, //
            DocumentSeed::class,      // Seeder independiente
            ClientDocumentSeed::class,//
            SettingFieldSeed::class,  //
            ConfigurableWordSeed::class,  //
            ClientCountrySeed::class,  //
        ]);

    }

    protected function truncate(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS =0;');
        foreach($tables as $table)
            DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS =1;');
    }

    protected function modify_users(){

        $users = User::all();
        $clients = Client::where('id', '<>',1)->get();

        foreach($users as $user){
            $user->clients_id = 1;
            $user->save();
        }

        foreach($clients as $client){
            $user = new User();
            $user->name = $client->name;
            $user->username = $client->contact_person;
            $user->email = $client->email;
            $user->email_verified_at = now();
            $user->password = bcrypt("12345");
            $user->phone = '333 000 00 00';
            $user->clients_id = $client->id;
            $user->roles_id = 5;
            $user->remember_token = Str::random(10);
            $user->save();
        }
    }

}
