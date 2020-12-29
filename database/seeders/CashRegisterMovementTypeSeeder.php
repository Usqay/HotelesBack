<?php

namespace Database\Seeders;

use App\Models\CashRegisterMovementType;
use Illuminate\Database\Seeder;

class CashRegisterMovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CashRegisterMovementType::create([
            'name' => 'Monto inicial',
            'in_out' => true,
            'is_countable' => false,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Pago de reservación',
            'in_out' => true,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Pago de venta',
            'in_out' => true,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Pago de credito',
            'in_out' => true,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Pago de consumo',
            'in_out' => true,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Pago de servicio',
            'in_out' => true,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Otro tipo de ingreso',
            'in_out' => true,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Vuelto',
            'in_out' => false,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Anulación de reservación',
            'in_out' => false,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Anulación de venta',
            'in_out' => false,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Otro tipo de salida',
            'in_out' => false,
            'is_countable' => true,
        ]);
        CashRegisterMovementType::create([
            'name' => 'Pago de reservación y consumo',
            'in_out' => true,
            'is_countable' => true,
        ]);
    }
}
