<?php

namespace App\Observers;

use App\Models\Currency;
use App\Models\Service;
use App\Models\ServicePrice;

class ServiceObserver
{
    /**
     * Handle the service "created" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function created(Service $service)
    {
        $currencies = Currency::all();
        foreach($currencies as $currency){
            ServicePrice::create([
                'service_id' => $service->id,
                'currency_id' => $currency->id,
                'sale_price' => 0,
            ]);
        }
    }

    /**
     * Handle the service "updated" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function updated(Service $service)
    {
        $currencies = Currency::all();

        foreach($currencies as $item){
            ServicePrice::firstOrCreate([
                'service_id' => $service->id,
                'currency_id' => $item->id,
            ],[
                'sale_price' => 0,
            ]);
        }
    }

    /**
     * Handle the service "deleted" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function deleted(Service $service)
    {
        //
    }

    /**
     * Handle the service "restored" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function restored(Service $service)
    {
        //
    }

    /**
     * Handle the service "force deleted" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function forceDeleted(Service $service)
    {
        //
    }
}
