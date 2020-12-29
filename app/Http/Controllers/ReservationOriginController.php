<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationOriginResource;
use App\Models\ReservationOrigin;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReservationOriginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = request()->query('q');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $reservationOrigins = ReservationOrigin::orderBy('id', 'ASC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%");
        })
        ->paginate($paginate);

        return ReservationOriginResource::collection($reservationOrigins);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReservationOrigin  $reservationOrigin
     * @return \Illuminate\Http\Response
     */
    public function show(ReservationOrigin $reservationOrigin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReservationOrigin  $reservationOrigin
     * @return \Illuminate\Http\Response
     */
    public function edit(ReservationOrigin $reservationOrigin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReservationOrigin  $reservationOrigin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservationOrigin $reservationOrigin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReservationOrigin  $reservationOrigin
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReservationOrigin $reservationOrigin)
    {
        //
    }
}
