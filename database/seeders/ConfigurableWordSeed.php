<?php

namespace Database\Seeders;

use App\Models\ConfigurableWord;
use Illuminate\Database\Seeder;


class ConfigurableWordSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        truncate('configurable_words');

        ConfigurableWord::insert([
            [
                'name'       => 'liviano',
                'label'       => 'Liviano',
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'       => 'integral',
                'label'       => 'Integral',
                'created_users_id' => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}
