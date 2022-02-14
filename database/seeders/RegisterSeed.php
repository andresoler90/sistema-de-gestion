<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Register;
use App\Models\User;
use App\Models\Country;
use Illuminate\Database\Seeder;

class RegisterSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where([['id','<>',1],['roles_id',5]])->get();

        $cc = ['10000000','10000001','10000002','10000003','10000004','10000005','10000006','10000007','10000008','10000009'];
        $register_type = ['L','I'];
        foreach($users as $key =>$user){
            $register = new Register();
            $register->code = Client::find($user->clients_id)->acronym.'001';
            $register->register_type = $register_type[array_rand($register_type)];
            $register->countries_id = Country::where('id','<>',43)->get()->random()->id;
            $register->identification_type = 'CC';
            $register->identification_number = $cc[array_rand($cc)];
            $register->business_name = 'Empresa '.($key+1);
            $register->telephone_contact = '300 000 00 00';
            $register->name_contact = 'Proveedor';
            $register->email_contact = 'proveedor@email.com';
            $register->register_assumed_by = 'C';
            $register->clients_id = $user->clients_id;
            $register->requesting_users_id = $user->id;
            $register->created_users_id = $user->id;
            $register->save();
        }
    }
}
