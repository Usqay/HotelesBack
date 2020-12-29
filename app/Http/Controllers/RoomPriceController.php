<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomPriceCreateRequest;
use App\Http\Requests\RoomPriceUpdateRequest;
use App\Http\Resources\RoomPriceResource;
use App\Models\RoomPrice;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoomPriceController extends Controller
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
     * @param  \App\Http\Requests\RoomPriceCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomPriceCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $roomPrice = RoomPrice::updateOrCreate(
                ['room_id' => $request->room_id, 'currency_id' => $request->currency_id],
                ['day_price' => $request->day_price, 'hour_price' => $request->hour_price]
            );

            if($roomPrice->wasChanged()){
                $this->saveUserLog($roomPrice, 'update');
            }else{
                $this->saveUserLog($roomPrice);
            }
        
            DB::commit();
            
            return $this->successResponse(new RoomPriceResource($roomPrice), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoomPrice  $roomPrice
     * @return \Illuminate\Http\Response
     */
    public function show(RoomPrice $roomPrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RoomPrice  $roomPrice
     * @return \Illuminate\Http\Response
     */
    public function edit(RoomPrice $roomPrice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RoomPriceUpdateRequest  $request
     * @param  \App\Models\RoomPrice  $roomPrice
     * @return \Illuminate\Http\Response
     */
    public function update(RoomPriceUpdateRequest $request, RoomPrice $roomPrice)
    {        
        try{
            
            DB::beginTransaction();
            
            $roomPrice->fill($request->toArray());
            $roomPrice->save();
        
            $this->saveUserLog($roomPrice, 'update');

            DB::commit();
            
            return $this->successResponse(new RoomPriceResource($roomPrice));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $roomPrice
     * @return \Illuminate\Http\Response
     */
    public function destroy($roomPrice)
    {
        $roomPrice = RoomPrice::withTrashed()->findOrFail($roomPrice);

        if ($roomPrice->trashed()) {
            $roomPrice->restore();
            $this->saveUserLog($roomPrice, 'restore');
        } else {
            $roomPrice->delete();
            $this->saveUserLog($roomPrice, 'delete');
        }

        return $this->successResponse(new RoomPriceResource($roomPrice));
    }
}
