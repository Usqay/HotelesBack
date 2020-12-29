<?php

namespace App\Providers;

use App\Models\ElectronicVoucher;
use App\Models\Product;
use App\Models\ProductMovement;
use App\Models\Reservation;
use App\Models\ReservationPayment;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\SaleService;
use App\Models\Service;
use App\Observers\ElectronicVoucherObserver;
use App\Observers\ProductMovementObserver;
use App\Observers\ProductObserver;
use App\Observers\ReservationObserver;
use App\Observers\ReservationPaymentObserver;
use App\Observers\ReservationRoomObserver;
use App\Observers\RoomObserver;
use App\Observers\SaleObserver;
use App\Observers\SaleProductObserver;
use App\Observers\SaleServiceObserver;
use App\Observers\ServiceObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Product::observe(ProductObserver::class);
        Room::observe(RoomObserver::class);
        Service::observe(ServiceObserver::class);
        ProductMovement::observe(ProductMovementObserver::class);
        ReservationRoom::observe(ReservationRoomObserver::class);
        Reservation::observe(ReservationObserver::class);
        ReservationPayment::observe(ReservationPaymentObserver::class);
        SaleProduct::observe(SaleProductObserver::class);
        SaleService::observe(SaleServiceObserver::class);
        Sale::observe(SaleObserver::class);
        ElectronicVoucher::observe(ElectronicVoucherObserver::class);
    }
}
