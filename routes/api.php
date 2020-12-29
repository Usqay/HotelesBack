<?php

use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CashRegisterMovementController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CurrencyRateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\ElectronicVoucherController;
use App\Http\Controllers\ElectronicVoucherTypeController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\PrinterTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomPriceController;
use App\Http\Controllers\RoomProductController;
use App\Http\Controllers\RoomStatusController;
use App\Http\Controllers\StoreHouseController;
use App\Http\Controllers\StoreHouseMovementController;
use App\Http\Controllers\StoreHouseMovementTypeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationGuestController;
use App\Http\Controllers\ReservationOriginController;
use App\Http\Controllers\ReservationPaymentController;
use App\Http\Controllers\ReservationRoomController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceProductController;
use App\Http\Controllers\SunatCodesController;
use App\Http\Controllers\SystemConfigurationController;
use App\Http\Controllers\TurnChangeController;
use App\Http\Controllers\TurnController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilitiesController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('register', 'PassportAuthController@register');
Route::post('login', [PassportAuthController::class, 'login']);
Route::get('enviroments', [UtilitiesController::class, 'enviroments']);
Route::get('currency-rates-today/{base}', [UtilitiesController::class, 'currency_rates']);

Route::middleware('auth:api')->group(function(){

    Route::apiResource('currencies', CurrencyController::class);
    Route::apiResource('currency-rates', CurrencyRateController::class);
    Route::apiResource('cash-registers', CashRegisterController::class);
    Route::apiResource('cash-register-movements', CashRegisterMovementController::class);
    Route::apiResource('room-categories', RoomCategoryController::class);
    Route::apiResource('room-statuses', RoomStatusController::class);
    Route::apiResource('room-prices', RoomPriceController::class)->except(['index', 'show']);
    Route::apiResource('room-products', RoomProductController::class)->except(['index', 'show']);
    Route::apiResource('rooms', RoomController::class);
    Route::post('room-reserve', [RoomController::class, 'reserve']);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('product-stocks', ProductStockController::class)->only(['index']);
    
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('service-products', ServiceProductController::class)->except(['index', 'show']);

    Route::apiResource('turns', TurnController::class);
    Route::apiResource('turn-changes', TurnChangeController::class);

    Route::apiResource('payment-methods', PaymentMethodController::class);

    Route::apiResource('store-houses', StoreHouseController::class);
    Route::apiResource('store-house-movements', StoreHouseMovementController::class)->only(['index', 'store', 'show']);
    Route::apiResource('store-house-movement-types', StoreHouseMovementTypeController::class)->only(['index']);

    Route::apiResource('document-types', DocumentTypeController::class)->only(['index']);
    Route::apiResource('genders', GenderController::class)->only(['index']);
    Route::apiResource('people', PeopleController::class)->only(['index', 'store']);
    Route::apiResource('guests', GuestController::class)->only(['store']);
    Route::apiResource('reservation-origins', ReservationOriginController::class)->only(['index']);
    Route::apiResource('reservation-guests', ReservationGuestController::class)->only(['store']);
    Route::apiResource('reservation-rooms', ReservationRoomController::class)->only(['store', 'destroy', 'index']);
    Route::apiResource('reservation-payments', ReservationPaymentController::class);

    Route::apiResource('reservations', ReservationController::class);

    Route::apiResource('system-configurations', SystemConfigurationController::class)->only(['index', 'store']);
    
    Route::apiResource('roles', RolesController::class);
    Route::apiResource('permissions', PermissionController::class)->only(['index', 'store']);
    Route::apiResource('users', UserController::class);

    Route::apiResource('sunat-codes', SunatCodesController::class)->only(['index']);

    Route::apiResource('sales', SaleController::class);

    Route::apiResource('printers', PrinterController::class);
    Route::apiResource('printer-types', PrinterTypeController::class)->only(['index']);

    Route::apiResource('electronic-vouchers', ElectronicVoucherController::class);
    Route::apiResource('electronic-voucher-types', ElectronicVoucherTypeController::class)->only(['index']);

    Route::post('report-rooms', [ReportsController::class, 'rooms']);
    Route::post('report-reservations', [ReportsController::class, 'reservations']);
    Route::post('report-sales', [ReportsController::class, 'sales']);
    Route::post('report-dayli', [ReportsController::class, 'dayli']);

    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    
    Route::post('logout', [PassportAuthController::class, 'logout']);
});