<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create(['name' => 'Efectivo']);
        PaymentMethod::create(['name' => 'Visa']);
        PaymentMethod::create(['name' => 'Mastercard']);
        PaymentMethod::create(['name' => 'Transferencia']);
        PaymentMethod::create(['name' => 'Yape']);
        PaymentMethod::create(['name' => 'Lukita']);
        PaymentMethod::create(['name' => 'Plin']);
        PaymentMethod::create(['name' => 'Otro']);
    }
}
