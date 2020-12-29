<?php

namespace App\Observers;

use App\Models\ProductMovement;
use App\Models\Sale;
use App\Models\SaleProduct;

class SaleProductObserver
{
    /**
     * Handle the sale product "created" event.
     *
     * @param  \App\Models\SaleProduct  $saleProduct
     * @return void
     */
    public function created(SaleProduct $saleProduct)
    {
        $sale = Sale::findOrFail($saleProduct->sale_id);
        ProductMovement::create([
            'product_id' => $saleProduct->product_id,
            'quantity' => $saleProduct->quantity,
            'store_house_movement_id' => $sale->store_house_movement_id,
            'product_movement_type_id' => '6',
        ]);
    }

    /**
     * Handle the sale product "updated" event.
     *
     * @param  \App\Models\SaleProduct  $saleProduct
     * @return void
     */
    public function updated(SaleProduct $saleProduct)
    {
        //
    }

    /**
     * Handle the sale product "deleted" event.
     *
     * @param  \App\Models\SaleProduct  $saleProduct
     * @return void
     */
    public function deleted(SaleProduct $saleProduct)
    {
        //
    }

    /**
     * Handle the sale product "restored" event.
     *
     * @param  \App\Models\SaleProduct  $saleProduct
     * @return void
     */
    public function restored(SaleProduct $saleProduct)
    {
        //
    }

    /**
     * Handle the sale product "force deleted" event.
     *
     * @param  \App\Models\SaleProduct  $saleProduct
     * @return void
     */
    public function forceDeleted(SaleProduct $saleProduct)
    {
        //
    }
}
