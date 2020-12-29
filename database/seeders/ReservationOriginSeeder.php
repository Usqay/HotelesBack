<?php

namespace Database\Seeders;

use App\Models\ReservationOrigin;
use Illuminate\Database\Seeder;

class ReservationOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReservationOrigin::create(['name' => 'Directa']);
        ReservationOrigin::create(['name' => 'Telefonica']);
        ReservationOrigin::create(['name' => 'Correo electronico']);
        ReservationOrigin::create(['name' => 'Redes sociales']);
        ReservationOrigin::create(['name' => 'Booking']);
        ReservationOrigin::create(['name' => 'Despegar']);
        ReservationOrigin::create(['name' => 'Trivago']);
        ReservationOrigin::create(['name' => 'Otro']);
    }
}
