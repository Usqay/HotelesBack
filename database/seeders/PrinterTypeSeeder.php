<?php

namespace Database\Seeders;

use App\Models\PrinterType;
use Illuminate\Database\Seeder;

class PrinterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PrinterType::create(['name' => 'Red']);
        PrinterType::create(['name' => 'Usb']);
    }
}
