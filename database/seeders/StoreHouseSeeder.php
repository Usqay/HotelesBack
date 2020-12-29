<?php

namespace Database\Seeders;

use App\Models\StoreHouse;
use Illuminate\Database\Seeder;

class StoreHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreHouse::create([
            'name' => 'Principal',
            'address' => null,
            'description' => 'Almacen de prueba',
            'is_base' => true,
        ]);
    }
}
