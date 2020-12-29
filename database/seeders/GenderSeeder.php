<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gender::create(['name' => 'Sin especificar']);
        Gender::create(['name' => 'Masculino']);
        Gender::create(['name' => 'Femenino']);
        Gender::create(['name' => 'Otro']);
    }
}
