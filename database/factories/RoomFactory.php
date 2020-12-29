<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->numberBetween(100,200);

        return [
            'name' => $name,
            'description' => $this->faker->sentence(15),
            'capacity' => $this->faker->numberBetween(1,5),
            'room_category_id' => $this->faker->numberBetween(1,3),
            'room_status_id' => $this->faker->numberBetween(1,3),
        ];
    }
}
