<?php

namespace App\Observers;

use App\Models\ProductMovement;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\SaleService;
use App\Models\ServiceProduct;
use App\Models\StoreHouse;
use App\Models\StoreHouseMovement;

class SaleObserver
{
    /**
     * Handle the sale "created" event.
     *
     * @param  \App\Models\Sale  $sale
     * @return void
     */
    public function created(Sale $sale)
    {
        //
    }

    /**
     * Handle the sale "updated" event.
     *
     * @param  \App\Models\Sale  $sale
     * @return void
     */
    public function updated(Sale $sale)
    {
        //
    }

    /**
     * Handle the sale "deleted" event.
     *
     * @param  \App\Models\Sale  $sale
     * @return void
     */
    public function deleted(Sale $sale)
    {
        $products = SaleProduct::where('sale_id', '=', $sale->id)->get();
        $services = SaleService::where('sale_id', '=', $sale->id)->get();
        $storeHouse = StoreHouse::where('is_base', '=', true)->first();
        
        $storeHouseMovement = StoreHouseMovement::create([
            'store_house_id' => $storeHouse->id,
            'store_house_movement_type_id' => '6',
            'description' => 'Ingreso de productos por anulación de venta',
        ]);

        foreach($products as $product){
            ProductMovement::create([
                'product_id' => $product->product_id,
                'quantity' => $product->quantity,
                'store_house_movement_id' => $storeHouseMovement->id,
                'product_movement_type_id' => '5',
            ]);
        }

        foreach($services as $service){
            $serviceProducts = ServiceProduct::where('service_id', '=', $service->service_id)->get();
            
            foreach($serviceProducts as $product){
                ProductMovement::create([
                    'product_id' => $product->product_id,
                    'quantity' => $product->quantity,
                    'store_house_movement_id' => $storeHouseMovement->id,
                    'product_movement_type_id' => '5',
                ]);
            }
        }
    }

    /**
     * Handle the sale "restored" event.
     *
     * @param  \App\Models\Sale  $sale
     * @return void
     */
    public function restored(Sale $sale)
    {
        //
    }

    /**
     * Handle the sale "force deleted" event.
     *
     * @param  \App\Models\Sale  $sale
     * @return void
     */
    public function forceDeleted(Sale $sale)
    {
        //
    }
}
