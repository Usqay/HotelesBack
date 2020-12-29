<?php

namespace App\Observers;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\StoreHouse;

class ProductObserver
{
    /**
     * Handle the product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        $storeHouses = StoreHouse::all();
        foreach($storeHouses as $storeHouse){
            ProductStock::create([
                'product_id' => $product->id,
                'store_house_id' => $storeHouse->id,
                'stock' => 0,
            ]);
        }

        $currencies = Currency::all();
        foreach($currencies as $currency){
            ProductPrice::create([
                'product_id' => $product->id,
                'currency_id' => $currency->id,
                'purchase_price' => 0,
                'sale_price' => 0,
            ]);
        }
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        $currencies = Currency::all();

        foreach($currencies as $item){
            ProductPrice::firstOrCreate([
                'product_id' => $product->id,
                'currency_id' => $item->id,
            ],[
                'purchase_price' => 0,
                'sale_price' => 0,
            ]);
        }
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
