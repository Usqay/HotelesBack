<?php

namespace App\Observers;

use App\Models\CurrencyRate;
use App\Models\ProductMovement;
use App\Models\ReservationRoom;
use App\Models\RoomProduct;
use App\Models\StoreHouse;
use App\Models\StoreHouseMovement;
use Exception;
use Illuminate\Support\Facades\DB;

class ReservationRoomObserver
{
    /**
     * Handle the reservation room "created" event.
     *
     * @param  \App\Models\ReservationRoom  $odel=ReservationRoom
     * @return void
     */
    public function created(ReservationRoom $ReservationRoom)
    {
        $roomProducts = RoomProduct::where('room_id', '=', $ReservationRoom->room_id)->withTrashed()->get();
        $storeHouse = StoreHouse::where('is_base', '=', true)->first();
        $currencyRate = CurrencyRate::where('currency_id', '=', $ReservationRoom->currency_id)
        ->orderBy('rate_date', 'desc')
        ->first();

        try{

            DB::beginTransaction();

            if(\count($roomProducts) > 0){
                $storeHouseMovement = StoreHouseMovement::create([
                    'store_house_id' => $storeHouse->id,
                    'store_house_movement_type_id' => '10',
                    'description' => 'Salida por insumo de habitación alquilada',
                ]);
        
                foreach($roomProducts as $roomProduct){
                    ProductMovement::create([
                        'product_id' => $roomProduct->product_id,
                        'quantity' => $roomProduct->quantity,
                        'store_house_movement_id' => $storeHouseMovement->id,
                        'product_movement_type_id' => '9',
                    ]);
                }
            }

            if(isset($currencyRate)){
                $ReservationRoom->rate_value = $currencyRate->rate_value;
                $ReservationRoom->update();
            }
            

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }

    /**
     * Handle the reservation room "updated" event.
     *
     * @param  \App\Models\ReservationRoom  $ReservationRoom
     * @return void
     */
    public function updated(ReservationRoom $ReservationRoom)
    {
        //
    }

    /**
     * Handle the reservation room "deleted" event.
     *
     * @param  \App\Models\ReservationRoom  $ReservationRoom
     * @return void
     */
    public function deleted(ReservationRoom $ReservationRoom)
    {
        $roomProducts = RoomProduct::where('room_id', '=', $ReservationRoom->room_id)->withTrashed()->get();
        $storeHouse = StoreHouse::where('is_base', '=', true)->first();
        try{

            DB::beginTransaction();

            if(\count($roomProducts) > 0){
                $storeHouseMovement = StoreHouseMovement::create([
                    'store_house_id' => $storeHouse->id,
                    'store_house_movement_type_id' => '6',
                    'description' => 'Ingreso por insumo de habitación alquilada eliminada',
                ]);
        
                foreach($roomProducts as $roomProduct){
                    ProductMovement::create([
                        'product_id' => $roomProduct->product_id,
                        'quantity' => $roomProduct->quantity,
                        'store_house_movement_id' => $storeHouseMovement->id,
                        'product_movement_type_id' => '5',
                    ]);
                }
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }

    /**
     * Handle the reservation room "restored" event.
     *
     * @param  \App\Models\ReservationRoom  $ReservationRoom
     * @return void
     */
    public function restored(ReservationRoom $ReservationRoom)
    {
        //
    }

    /**
     * Handle the reservation room "force deleted" event.
     *
     * @param  \App\Models\ReservationRoom  $ReservationRoom
     * @return void
     */
    public function forceDeleted(ReservationRoom $ReservationRoom)
    {
        //
    }
}
