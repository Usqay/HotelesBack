<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHouseCreateRequest;
use App\Http\Requests\StoreHouseUpdateRequest;
use App\Http\Resources\StoreHouseResource;
use App\Models\StoreHouse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StoreHouseController extends Controller
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

        $storeHouses = StoreHouse::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%")
            ->orWhere("address", "like", "%$q%")
            ->orWhere("description", "like", "%$q%");
        })
        ->paginate($paginate);

        return StoreHouseResource::collection($storeHouses);
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
    public function store(StoreHouseCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $storeHouse = StoreHouse::create($request->toArray());
        
            $this->saveUserLog($storeHouse);

            DB::commit();
            
            return $this->successResponse(new StoreHouseResource($storeHouse), Response::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoreHouse  $storeHouse
     * @return \Illuminate\Http\Response
     */
    public function show(StoreHouse $storeHouse)
    {
        return $this->successResponse(new StoreHouseResource($storeHouse));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreHouse  $storeHouse
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreHouse $storeHouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreHouse  $storeHouse
     * @return \Illuminate\Http\Response
     */
    public function update(StoreHouseUpdateRequest $request, StoreHouse $storeHouse)
    {
        try{
            
            DB::beginTransaction();

            if($request->is_base){
                StoreHouse::where('is_base', '=', 1)
                ->where('id', '!=', $storeHouse->id)
                ->update([
                    'is_base' => 0
                ]);
            }
            
            $storeHouse->fill($request->toArray());
            $storeHouse->save();

            $this->saveUserLog($storeHouse, 'update');

            DB::commit();
            
            return $this->successResponse(new StoreHouseResource($storeHouse));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreHouse  $storeHouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($storeHouse)
    {
        $storeHouse = StoreHouse::withTrashed()->findOrFail($storeHouse);

        if ($storeHouse->trashed()) {
            $storeHouse->restore();
            $this->saveUserLog($storeHouse, 'restore');
        } else {
            $storeHouse->delete();
            $this->saveUserLog($storeHouse, 'delete');
        }

        return $this->successResponse(new StoreHouseResource($storeHouse));
    }
}
