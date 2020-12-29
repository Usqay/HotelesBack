<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRoomCreateRequest;
use App\Http\Resources\ReservationRoomResource;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ReservationRoomCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationRoomCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($request->reservation_id);
            $total_price = 0;

            if($request->price_type == 'day'){
                $total_price = $reservation->total_days * $request->price_value; 
            }else{
                $total_price = $reservation->total_hours * $request->price_value;
            }
            
            $reservationRoom = ReservationRoom::updateOrCreate([
                'reservation_id' => $request->reservation_id,
                'room_id' => $request->room_id
            ], [
                'currency_id' => $request->currency_id,
                'price_type' => $request->price_type,
                'price_value' => $request->price_value,
                'total_price' => $total_price,
            ]);
            
            $reservation->update(['token_for_observer' => Str::random(10)]);
            
            $this->saveUserLog($reservationRoom);

            DB::commit();
            
            return $this->successResponse(new ReservationRoomResource($reservationRoom), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReservationRoom  $reservationRoom
     * @return \Illuminate\Http\Response
     */
    public function show(ReservationRoom $reservationRoom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReservationRoom  $reservationRoom
     * @return \Illuminate\Http\Response
     */
    public function edit(ReservationRoom $reservationRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReservationRoom  $reservationRoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservationRoom $reservationRoom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReservationRoom  $reservationRoom
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReservationRoom $reservationRoom)
    {
        Room::findOrFail($reservationRoom->room_id)
        ->update([
            'room_status_id' => 1
        ]);

        $reservationRoom->delete();
        $this->saveUserLog($reservationRoom, 'delete');

        $reservation = Reservation::findOrfail($reservationRoom->reservation_id);
        
        $reservation->update(['token_for_observer' => Str::random(10)]);
        
        return $this->successResponse(new ReservationRoomResource($reservationRoom));
    }
}
