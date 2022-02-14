<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $name = $this->faker->company();

        return [
            'name' => $name,
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'countries_id' => Country::all()->random()->id,
            'acronym' => $this->faker->unique()->regexify('[A-Z]{3}'),
            'contact_person' => $name,
            'created_users_id' => 1,
        ];
    }
}
