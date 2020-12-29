<?php

namespace App\Observers;

use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\ReservationTotal;
use App\Models\Room;
use App\Models\Sale;

class ReservationObserver
{
    /**
     * Handle the reservation "created" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function created(Reservation $reservation)
    {
        //
    }

    /**
     * Handle the reservation "updated" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function updated(Reservation $reservation)
    {
        $reservationRooms = ReservationRoom::where('reservation_id', '=', $reservation->id)->get();
        $baseCurrency = Currency::where('is_base', '=', true)->first();
        $saleTotals = Sale::where('reservation_id', '=', $reservation->id)->with('totals')->get();

        ReservationTotal::where('reservation_id', '=', $reservation->id)->forceDelete();

        foreach($reservationRooms as $reservationRoom){
            $reservationTotal = ReservationTotal::firstOrCreate([
                'reservation_id' => $reservation->id,
                'currency_id' => $baseCurrency->id,
                'total_by' => '0'
            ]);
            $total = 0;
            if($reservationRoom->price_type == 'hour'){
                $total = $reservationRoom->price_value * $reservation->total_hours;
            }else{
                $total = $reservationRoom->price_value * $reservation->total_days;
            }
            if($reservationRoom->rate_value != 1){
                $total = $total * $reservationRoom->rate_value;
            }
            $reservationTotal->increment('total', $total);

            switch($reservation->reservation_state_id){
                case 2 :
                    Room::where('id', '=', $reservationRoom->room_id)->update(['room_status_id' => 2]);
                break;
                case 3 :
                    Room::where('id', '=', $reservationRoom->room_id)->update(['room_status_id' => 1]);
                break;
                case 4 :
                    Room::where('id', '=', $reservationRoom->room_id)->update(['room_status_id' => 1]);
                break;
            }
        }
        
        foreach ($saleTotals as $saleTotal) {
            foreach ($saleTotal->totals as $total) {
                $reservationTotal = ReservationTotal::firstOrCreate([
                    'reservation_id' => $reservation->id,
                    'currency_id' => $total->currency_id,
                    'total_by' => '1'
                ]);
                
                $reservationTotal->increment('total', $total->total);
            }
        }
    }

    /**
     * Handle the reservation "deleted" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function deleted(Reservation $reservation)
    {
        //
    }

    /**
     * Handle the reservation "restored" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function restored(Reservation $reservation)
    {
        //
    }

    /**
     * Handle the reservation "force deleted" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function forceDeleted(Reservation $reservation)
    {
        //
    }
}
