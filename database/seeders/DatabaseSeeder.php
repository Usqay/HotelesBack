<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CurrencySeeder::class,
            RoomCategorySeeder::class,
            RoomStatusSeeder::class,
            StoreHouseSeeder::class,
            StoreHouseMovementTypeSeeder::class,
            ProductMovementTypeSeeder::class,
            CashRegisterSeeder::class,
            CashRegisterMovementTypeSeeder::class,
            GenderSeeder::class,
            DocumentTypeSeeder::class,
            PeopleSeeder::class,
            ReservationOriginSeeder::class,
            ReservationStateSeeder::class,
            TurnSeeder::class,
            SystemConfigurationSeeder::class,
            PaymentMethodSeeder::class,
            ElectronicVoucherTypeSeeder::class,
            SaleStateSeeder::class,
            PrinterTypeSeeder::class,
            SunatCodesSeeder::class,
        ]);

        // \App\Models\Room::factory(15)->create();
        // \App\Models\Product::factory(40)->create();
        // \App\Models\Service::factory(20)->create();

        $roleStaff = Role::create(['name' => 'Staff']);
        $roleAdmin = Role::create(['name' => 'Administrador']);
        $roleReceptionist = Role::create(['name' => 'Recepcionista']);

        $permissions = [
            
            Permission::create(['name' => 'create-reservations']),
            Permission::create(['name' => 'read-reservations']),
            Permission::create(['name' => 'update-reservations']),
            Permission::create(['name' => 'delete-reservations']),

            Permission::create(['name' => 'create-currencies']),
            Permission::create(['name' => 'read-currencies']),
            Permission::create(['name' => 'update-currencies']),
            Permission::create(['name' => 'delete-currencies']),
            
            Permission::create(['name' => 'create-turns']),
            Permission::create(['name' => 'read-turns']),
            Permission::create(['name' => 'update-turns']),
            Permission::create(['name' => 'delete-turns']),
            
            Permission::create(['name' => 'create-room-categories']),
            Permission::create(['name' => 'read-room-categories']),
            Permission::create(['name' => 'update-room-categories']),
            Permission::create(['name' => 'delete-room-categories']),
            
            Permission::create(['name' => 'create-room-statuses']),
            Permission::create(['name' => 'read-room-statuses']),
            Permission::create(['name' => 'update-room-statuses']),
            Permission::create(['name' => 'delete-room-statuses']),
            
            Permission::create(['name' => 'read-system-configurations']),
            Permission::create(['name' => 'update-system-configurations']),
            
            Permission::create(['name' => 'create-rooms']),
            Permission::create(['name' => 'read-rooms']),
            Permission::create(['name' => 'update-rooms']),
            Permission::create(['name' => 'delete-rooms']),
            
            Permission::create(['name' => 'create-cash-registers']),
            Permission::create(['name' => 'read-cash-registers']),
            Permission::create(['name' => 'update-cash-registers']),
            Permission::create(['name' => 'delete-cash-registers']),
            
            Permission::create(['name' => 'create-cash-register-movements']),
            Permission::create(['name' => 'read-cash-register-movements']),
            Permission::create(['name' => 'update-cash-register-movements']),
            Permission::create(['name' => 'delete-cash-register-movements']),
            
            Permission::create(['name' => 'create-store-houses']),
            Permission::create(['name' => 'read-store-houses']),
            Permission::create(['name' => 'update-store-houses']),
            Permission::create(['name' => 'delete-store-houses']),
            
            Permission::create(['name' => 'create-store-house-movements']),
            Permission::create(['name' => 'read-store-house-movements']),
            Permission::create(['name' => 'update-store-house-movements']),
            Permission::create(['name' => 'delete-store-house-movements']),
            
            Permission::create(['name' => 'create-products']),
            Permission::create(['name' => 'read-products']),
            Permission::create(['name' => 'update-products']),
            Permission::create(['name' => 'delete-products']),
            
            Permission::create(['name' => 'create-services']),
            Permission::create(['name' => 'read-services']),
            Permission::create(['name' => 'update-services']),
            Permission::create(['name' => 'delete-services']),
            
            Permission::create(['name' => 'create-roles']),
            Permission::create(['name' => 'read-roles']),
            Permission::create(['name' => 'update-roles']),
            Permission::create(['name' => 'delete-roles']),
            
            Permission::create(['name' => 'create-users']),
            Permission::create(['name' => 'read-users']),
            Permission::create(['name' => 'update-users']),
            Permission::create(['name' => 'delete-users']),
            
            Permission::create(['name' => 'create-sales']),
            Permission::create(['name' => 'read-sales']),
            Permission::create(['name' => 'update-sales']),
            Permission::create(['name' => 'delete-sales']),
            
            Permission::create(['name' => 'create-electronic-vouchers']),
            Permission::create(['name' => 'read-electronic-vouchers']),
            Permission::create(['name' => 'update-electronic-vouchers']),
            Permission::create(['name' => 'delete-electronic-vouchers']),

            Permission::create(['name' => 'read-report-reservations']),
            Permission::create(['name' => 'read-report-rooms']),
            Permission::create(['name' => 'read-report-sales']),
            Permission::create(['name' => 'read-report-cash_registers']),
            Permission::create(['name' => 'read-report-dayli']),
        ];

        $roleStaff->syncPermissions($permissions);

        $user = User::create([
            'name' => 'Soporte',
            'email' => 'soporte@sistemausqay.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => Hash::make('Usqay2020Y#'),
            'people_id' => 1
        ]);

        $user->assignRole('Staff');
    }
}
