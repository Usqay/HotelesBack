<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SunatCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sunatCode = SunatCode::inRandomOrder()->first();
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(15),
            'sunat_code' => $sunatCode->code,
        ];
    }
}
