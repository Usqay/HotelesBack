<?php

namespace App\Observers;

use App\Models\Currency;
use App\Models\Room;
use App\Models\RoomPrice;

class RoomObserver
{
    /**
     * Handle the room "created" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function created(Room $room)
    {
        $currencies = Currency::all();
        foreach($currencies as $currency){
            RoomPrice::create([
                'room_id' => $room->id,
                'currency_id' => $currency->id,
                'day_price' => 0,
                'hour_price' => 0,
            ]);
        }
    }

    /**
     * Handle the room "updated" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function updated(Room $room)
    {
        //
    }

    /**
     * Handle the room "deleted" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function deleted(Room $room)
    {
        //
    }

    /**
     * Handle the room "restored" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function restored(Room $room)
    {
        //
    }

    /**
     * Handle the room "force deleted" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function forceDeleted(Room $room)
    {
        //
    }
}
