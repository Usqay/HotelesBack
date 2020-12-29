<?php

namespace Database\Seeders;

use App\Models\People;
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        People::create([
            'name' => 'Staff',
            'last_name' => 'Staff',
            'full_name' => 'Staff',
            'gender_id' => 1,
            'document_type_id' => 1,
            'document_number' => '00000000',
            'address' => 'Piura, Perú',
            'phone_number' => null,
            'email' => 'staff@victormorenope.com',
            'birthday_date' => null,
        ]);
    }
}
