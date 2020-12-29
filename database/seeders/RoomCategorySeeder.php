<?php

namespace Database\Seeders;

use App\Models\RoomCategory;
use Illuminate\Database\Seeder;

class RoomCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoomCategory::create([
                'id' => 1,
                'name' => 'Simple',
                'capacity' => '1'
        ]);
        RoomCategory::create([
            'id' => 2,
            'name' => 'Doble',
            'capacity' => '2'
        ]);
        RoomCategory::create([
            'id' => 3,
            'name' => 'Matrimonial',
            'capacity' => '2'
        ]);
    }
}
