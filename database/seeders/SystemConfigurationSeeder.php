<?php

namespace Database\Seeders;

use App\Models\SystemConfiguration;
use Illuminate\Database\Seeder;

class SystemConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemConfiguration::create([
            'key' => 'business_name',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'commercial_name',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'business_address',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'ruc',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'business_holder_name',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'business_holder_document_number',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'notifications_emails',
            'value' => '[]'
        ]);
        SystemConfiguration::create([
            'key' => 'notify_reservation_created',
            'value' => true
        ]);
        SystemConfiguration::create([
            'key' => 'notify_reservation_updated',
            'value' => true
        ]);
        SystemConfiguration::create([
            'key' => 'notify_reservation_canceled',
            'value' => true
        ]);
        SystemConfiguration::create([
            'key' => 'notify_turn_changed',
            'value' => true
        ]);
        SystemConfiguration::create([
            'key' => 'notify_storehouse_movement',
            'value' => true
        ]);
        SystemConfiguration::create([
            'key' => 'billing_route',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'billing_token',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'invoice_series',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'invoice_auto_increment',
            'value' => 0
        ]);
        SystemConfiguration::create([
            'key' => 'ballot_series',
            'value' => 'Replace this value'
        ]);
        SystemConfiguration::create([
            'key' => 'ballot_auto_increment',
            'value' => 0
        ]);
        SystemConfiguration::create([
            'key' => 'ncf_series',
            'value' => 'FNC1'
        ]);
        SystemConfiguration::create([
            'key' => 'ncf_auto_increment',
            'value' => 0
        ]);
        SystemConfiguration::create([
            'key' => 'ncb_series',
            'value' => 'BNC1'
        ]);
        SystemConfiguration::create([
            'key' => 'ncb_auto_increment',
            'value' => 0
        ]);
        SystemConfiguration::create([
            'key' => 'ballot_printer',
            'value' => ''
        ]);
        SystemConfiguration::create([
            'key' => 'invoice_printer',
            'value' => ''
        ]);
        SystemConfiguration::create([
            'key' => 'base_printer',
            'value' => ''
        ]);
        SystemConfiguration::create([
            'key' => 'print_logo',
            'value' => true
        ]);
        SystemConfiguration::create([
            'key' => 'business_phone_number',
            'value' => 'Replace this value'
        ]);
    }
}
