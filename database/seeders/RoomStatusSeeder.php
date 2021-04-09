<?php

namespace Database\Seeders;

use App\Models\RoomStatus;
use Illuminate\Database\Seeder;

class RoomStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoomStatus::create([
            'id' => 1,
            'name' => 'Disponible'
        ]);
        RoomStatus::create([
            'id' => 2,
            'name' => 'Ocupada'
        ]);
        RoomStatus::create([
            'id' => 3,
            'name' => 'En mantenimiento'
        ]);
        RoomStatus::create([
            'id' => 4,
            'name' => 'Reservada'
        ]);
    }
}