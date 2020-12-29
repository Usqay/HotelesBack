<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationGuestStoreRequest;
use App\Http\Resources\ReservationGuestResource;
use App\Models\ReservationGuest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReservationGuestController extends Controller
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
     * @param  \App\Http\Requests\ReservationGuestStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationGuestStoreRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $reservationGuest = ReservationGuest::firstOrCreate($request->toArray());
            
            $this->saveUserLog($reservationGuest);

            DB::commit();
            
            return $this->successResponse(new ReservationGuestResource($reservationGuest), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReservationGuest  $reservationGuest
     * @return \Illuminate\Http\Response
     */
    public function show(ReservationGuest $reservationGuest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReservationGuest  $reservationGuest
     * @return \Illuminate\Http\Response
     */
    public function edit(ReservationGuest $reservationGuest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReservationGuest  $reservationGuest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservationGuest $reservationGuest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReservationGuest  $reservationGuest
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReservationGuest $reservationGuest)
    {
        //
    }
}
