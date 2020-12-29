<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SunatCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $registros = json_decode(file_get_contents(base_path('public/codigos_sunat.json')));

        $primero = 0;
        $ultimo = 1000;

        for($i = 0; $i < 54; $i++){
            $data = [];

            if($i === 53) $ultimo = count($registros);

            for($j=$primero; $j < ($ultimo); $j++){
                $data[] = [
                    'code' => $registros[$j]->codigo,
                    'description' => $registros[$j]->descripcion,
                ];
            }

            $primero = $primero + 1000;
            $ultimo = $primero + 1000;

            if(count($data) > 0){
                DB::table('sunat_codes')->insert($data);
            }
        }
    }
}
