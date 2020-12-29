<?php

namespace App\Observers;

use App\Models\ReservationPayment;
use App\Traits\Billing;

class ReservationPaymentObserver
{
    use Billing;
    /**
     * Handle the = reservation payment "created" event.
     *
     * @param  \App\Models\ReservationPayment  $ReservationPayment
     * @return void
     */
    public function created(ReservationPayment $ReservationPayment)
    {
        //
    }

    /**
     * Handle the = reservation payment "updated" event.
     *
     * @param  \App\Models\ReservationPayment  $ReservationPayment
     * @return void
     */
    public function updated(ReservationPayment $ReservationPayment)
    {
        //
    }

    /**
     * Handle the = reservation payment "deleted" event.
     *
     * @param  \App\Models\ReservationPayment  $ReservationPayment
     * @return void
     */
    public function deleted(ReservationPayment $ReservationPayment)
    {
        //
    }

    /**
     * Handle the = reservation payment "restored" event.
     *
     * @param  \App\Models\ReservationPayment  $ReservationPayment
     * @return void
     */
    public function restored(ReservationPayment $ReservationPayment)
    {
        //
    }

    /**
     * Handle the = reservation payment "force deleted" event.
     *
     * @param  \App\Models\ReservationPayment  $ReservationPayment
     * @return void
     */
    public function forceDeleted(ReservationPayment $ReservationPayment)
    {
        //
    }
}
