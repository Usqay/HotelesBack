<?php

namespace Database\Seeders;

use App\Models\ProductMovementType;
use Illuminate\Database\Seeder;

class ProductMovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductMovementType::create([
            'name' => 'Ingreso',
            'in_out' => true
        ]);
        ProductMovementType::create([
            'name' => 'Salida',
            'in_out' => false
        ]);
        ProductMovementType::create([
            'name' => 'Ingreso de producto por compra',
            'in_out' => true
        ]);
        ProductMovementType::create([
            'name' => 'Ingreso de producto por descargue',
            'in_out' => true
        ]);
        ProductMovementType::create([
            'name' => 'Ingreso de producto por devolucion',
            'in_out' => true
        ]);
        ProductMovementType::create([
            'name' => 'Salida de producto por venta',
            'in_out' => false
        ]);
        ProductMovementType::create([
            'name' => 'Salida de producto por descargue',
            'in_out' => false
        ]);
        ProductMovementType::create([
            'name' => 'Salida de producto por insumo de servicio',
            'in_out' => false
        ]);
        ProductMovementType::create([
            'name' => 'Salida de producto por insumo de habitación',
            'in_out' => false
        ]);
    }
}
