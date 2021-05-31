<?php

namespace Database\Seeders;

use App\Models\ElectronicVoucherType;
use Illuminate\Database\Seeder;

class ElectronicVoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ElectronicVoucherType::create(['name' => 'Factura']);
        ElectronicVoucherType::create(['name' => 'Boleta']);
        ElectronicVoucherType::create(['name' => 'Nota de venta']);
        ElectronicVoucherType::create(['name' => 'Nota de crédito']);
    }
}
