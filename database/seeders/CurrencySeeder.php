<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create([
            'id' => '1',
            'name' => 'Nuevo sol',
            'plural_name' => 'Soles',
            'symbol' => 'S/.',
            'code' => 'PEN',
            'is_base' => true,
        ]);
        // Currency::create([
        //     'id' => '2',
        //     'name' => 'Dolar',
        //     'plural_name' => 'Dolares',
        //     'symbol' => '$',
        //     'code' => 'USD',
        //     'is_base' => false,
        // ]);
    }
}
