<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\SunatCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sunatCode = SunatCode::inRandomOrder()->first();
        return [
            'name' => 'Service '.$this->faker->words(2, true),
            'description' => $this->faker->sentence(15),
            'sunat_code' => $sunatCode->code,
        ];
    }
}
