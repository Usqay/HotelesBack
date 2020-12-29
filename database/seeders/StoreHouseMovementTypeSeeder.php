<?php

namespace Database\Seeders;

use App\Models\StoreHouseMovementType;
use Illuminate\Database\Seeder;

class StoreHouseMovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreHouseMovementType::create([
            'name' => 'Ingreso',
            'in_out' => true
        ]);
        StoreHouseMovementType::create([
            'name' => 'Salida',
            'in_out' => false
        ]);
        StoreHouseMovementType::create([
            'name' => 'Movimiento entre almacenes',
            'in_out' => false
        ]);
        StoreHouseMovementType::create([
            'name' => 'Ingreso de productos por compra',
            'in_out' => true
        ]);
        StoreHouseMovementType::create([
            'name' => 'Ingreso de productos por descargue',
            'in_out' => true
        ]);
        StoreHouseMovementType::create([
            'name' => 'Ingreso de productos por devolucion',
            'in_out' => true
        ]);
        StoreHouseMovementType::create([
            'name' => 'Salida de productos por venta',
            'in_out' => false
        ]);
        StoreHouseMovementType::create([
            'name' => 'Salida de productos por descargue',
            'in_out' => false
        ]);
        StoreHouseMovementType::create([
            'name' => 'Salida de productos por insumo de servicio',
            'in_out' => false
        ]);
        StoreHouseMovementType::create([
            'name' => 'Salida de productos por insumo de habitación',
            'in_out' => false
        ]);
    }
}