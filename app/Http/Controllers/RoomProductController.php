<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomProductCreateRequest;
use App\Http\Requests\RoomProductUpdateRequest;
use App\Http\Resources\RoomProductResource;
use App\Models\RoomProduct;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoomProductController extends Controller
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
     * @param  \App\Http\Requests\RoomProductCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomProductCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $roomProduct = RoomProduct::updateOrCreate(
                ['room_id' => $request->room_id, 'product_id' => $request->product_id],
                ['quantity' => $request->quantity]
            );

            if($roomProduct->wasChanged()){
                $this->saveUserLog($roomProduct, 'update');
            }else{
                $this->saveUserLog($roomProduct);
            }
        
            DB::commit();
            
            return $this->successResponse(new RoomProductResource($roomProduct), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $roomProduct
     * @return \Illuminate\Http\Response
     */
    public function show(RoomProduct $roomProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $roomProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(RoomProduct $roomProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RoomProductUpdateRequest  $request
     * @param  \App\Models\RoomProduct  $roomProduct
     * @return \Illuminate\Http\Response
     */
    public function update(RoomProductUpdateRequest $request, RoomProduct $roomProduct)
    {        
        try{
            
            DB::beginTransaction();
            
            $roomProduct->fill($request->toArray());
            $roomProduct->save();
        
            $this->saveUserLog($roomProduct, 'update');

            DB::commit();
            
            return $this->successResponse(new RoomProductResource($roomProduct));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $roomProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy($roomProduct)
    {
        $roomProduct = RoomProduct::withTrashed()->findOrFail($roomProduct);

        if ($roomProduct->trashed()) {
            $roomProduct->restore();
            $this->saveUserLog($roomProduct, 'restore');
        } else {
            $roomProduct->delete();
            $this->saveUserLog($roomProduct, 'delete');
        }

        return $this->successResponse(new RoomProductResource($roomProduct));
    }
}
