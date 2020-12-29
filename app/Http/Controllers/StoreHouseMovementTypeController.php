<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoreHouseMovementTypeResource;
use App\Models\StoreHouseMovementType;
use Illuminate\Http\Request;

class StoreHouseMovementTypeController extends Controller
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

        $storeHouseMovementTypes = StoreHouseMovementType::orderBy('id', 'ASC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%");
        })
        ->paginate($paginate);

        return StoreHouseMovementTypeResource::collection($storeHouseMovementTypes);
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
     * @param  \App\Models\StoreHouseMovementType  $storeHouseMovementType
     * @return \Illuminate\Http\Response
     */
    public function show(StoreHouseMovementType $storeHouseMovementType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreHouseMovementType  $storeHouseMovementType
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreHouseMovementType $storeHouseMovementType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreHouseMovementType  $storeHouseMovementType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreHouseMovementType $storeHouseMovementType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreHouseMovementType  $storeHouseMovementType
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreHouseMovementType $storeHouseMovementType)
    {
        //
    }
}
