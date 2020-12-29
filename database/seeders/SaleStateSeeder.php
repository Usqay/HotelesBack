<?php

namespace Database\Seeders;

use App\Models\SaleState;
use Illuminate\Database\Seeder;

class SaleStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SaleState::create(['name' => 'Creada']);
        SaleState::create(['name' => 'Activa']);
        SaleState::create(['name' => 'Pagada']);
        SaleState::create(['name' => 'Anulada']);
    }
}
