<?php

namespace Database\Seeders;

use App\Models\ReservationState;
use Illuminate\Database\Seeder;

class ReservationStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReservationState::create(['name' => 'Pendiente']);
        ReservationState::create(['name' => 'Checked In']);
        ReservationState::create(['name' => 'Checked Out']);
        ReservationState::create(['name' => 'Anulada']);
    }
}
