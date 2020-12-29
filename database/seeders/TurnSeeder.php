<?php

namespace Database\Seeders;

use App\Models\Turn;
use Illuminate\Database\Seeder;

class TurnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Turn::create([
            'name' => 'Unico',
            'open_time' => '00:00',
            'close_time' => '23:59',
        ]);
    }
}
