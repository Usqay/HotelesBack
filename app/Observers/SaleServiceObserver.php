<?php

namespace App\Observers;

use App\Models\ProductMovement;
use App\Models\Sale;
use App\Models\SaleService;
use App\Models\ServiceProduct;

class SaleServiceObserver
{
    /**
     * Handle the sale service "created" event.
     *
     * @param  \App\Models\SaleService  $saleService
     * @return void
     */
    public function created(SaleService $saleService)
    {
        $serviceProducts = ServiceProduct::where('service_id', '=', $saleService->service_id)->get();
        $sale = Sale::findOrFail($saleService->sale_id);
        
        foreach($serviceProducts as $product){
            ProductMovement::create([
                'product_id' => $product->product_id,
                'quantity' => $product->quantity,
                'store_house_movement_id' => $sale->store_house_movement_id,
                'product_movement_type_id' => '8',
            ]);
        }
    }

    /**
     * Handle the sale service "updated" event.
     *
     * @param  \App\Models\SaleService  $saleService
     * @return void
     */
    public function updated(SaleService $saleService)
    {
        //
    }

    /**
     * Handle the sale service "deleted" event.
     *
     * @param  \App\Models\SaleService  $saleService
     * @return void
     */
    public function deleted(SaleService $saleService)
    {
        //
    }

    /**
     * Handle the sale service "restored" event.
     *
     * @param  \App\Models\SaleService  $saleService
     * @return void
     */
    public function restored(SaleService $saleService)
    {
        //
    }

    /**
     * Handle the sale service "force deleted" event.
     *
     * @param  \App\Models\SaleService  $saleService
     * @return void
     */
    public function forceDeleted(SaleService $saleService)
    {
        //
    }
}
