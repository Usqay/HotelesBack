<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentType::create(['name' => 'DNI', 'digits' => '8', 'code' => '1']);
        DocumentType::create(['name' => 'Carnet Ext.', 'digits' => '12', 'code' => '4']);
        DocumentType::create(['name' => 'RUC', 'digits' => '11', 'code' => '5']);
        DocumentType::create(['name' => 'Pasaporte', 'digits' => '12', 'code' => '7']);
        DocumentType::create(['name' => 'Cedula diplomatica de identidad.', 'digits' => '15', 'code' => 'A']);
        DocumentType::create(['name' => 'No domiciliado.', 'digits' => '15', 'code' => '0']);
        DocumentType::create(['name' => 'Otros', 'digits' => '15', 'code' => '-']);
    }
}
