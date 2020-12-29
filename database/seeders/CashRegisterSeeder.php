<?php

namespace Database\Seeders;

use App\Models\CashRegister;
use Illuminate\Database\Seeder;

class CashRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CashRegister::create([
            'name' => 'Recepción',
            'description' => null,
            'location' => null,
            'is_base' => true
        ]);
    }
}
