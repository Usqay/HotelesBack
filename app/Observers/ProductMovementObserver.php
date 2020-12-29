<?php

namespace App\Observers;

use App\Models\ProductMovement;
use App\Models\ProductMovementType;
use App\Models\ProductStock;
use App\Models\StoreHouseMovement;

class ProductMovementObserver
{
    /**
     * Handle the product "created" event.
     *
     * @param  \App\Models\ProductMovement  $productMovement
     * @return void
     */
    public function created(ProductMovement $productMovement)
    {
        $storeHouseMovement = StoreHouseMovement::findOrFail($productMovement->store_house_movement_id);

        $productMovementType = ProductMovementType::findOrFail($productMovement->product_movement_type_id);

        $productStock =  ProductStock::firstOrCreate([
            'product_id' => $productMovement->product_id,
            'store_house_id' => $storeHouseMovement->store_house_id,
        ], [
            'stock' => 0,
        ]);

        if($productMovementType->in_out){
            //Increment
            $productStock->increment('stock', $productMovement->quantity);
        }else{
            //Decrement
            $productStock->decrement('stock', $productMovement->quantity);
        }

    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Models\ProductMovement  $productMovement
     * @return void
     */
    public function updated(ProductMovement $productMovement)
    {
        //
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Models\ProductMovement  $productMovement
     * @return void
     */
    public function deleted(ProductMovement $productMovement)
    {
        //
    }

    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Models\ProductMovement  $productMovement
     * @return void
     */
    public function restored(ProductMovement $productMovement)
    {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Models\ProductMovement  $productMovement
     * @return void
     */
    public function forceDeleted(ProductMovement $productMovement)
    {
        //
    }
}
