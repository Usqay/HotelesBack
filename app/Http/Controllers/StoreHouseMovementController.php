<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHouseMovementCreateRequest;
use App\Http\Resources\StoreHouseMovementResource;
use App\Models\ProductMovement;
use App\Models\StoreHouseMovement;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StoreHouseMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = request()->query('q');
        $storeHouseId = request()->query('storeHouseId');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $storeHouseMovements = StoreHouseMovement::orderBy('id', 'DESC')
        ->where(function ($query) use ($q, $storeHouseId) {
            $query->where("store_house_id", "=", $storeHouseId);
        })
        ->paginate($paginate);

        return StoreHouseMovementResource::collection($storeHouseMovements);
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
    public function store(StoreHouseMovementCreateRequest $request)
    {
        try{
            if($request->store_house_movement_type_id == '3'){
                //First, we have to create an output movement
                DB::beginTransaction();
                
                $storeHouseMovementOutput = StoreHouseMovement::create([
                    'store_house_id' => $request->store_house_id,
                    'store_house_movement_type_id' => 2,
                    'description' => $request->description,
                ]);

                foreach($request->products as $product){
                    ProductMovement:: create([
                        'product_id' => $product['product_id'],
                        'store_house_movement_id' => $storeHouseMovementOutput->id,
                        'product_movement_type_id' => 2,
                        'quantity' => $product['quantity'],
                    ]);
                }

                $this->saveUserLog($storeHouseMovementOutput);

                DB::commit();

                //Second, the input movement
                
                DB::beginTransaction();
                
                $storeHouseMovementInput = StoreHouseMovement::create([
                    'store_house_id' => $request->second_store_house_id,
                    'store_house_movement_type_id' => 1,
                    'description' => $request->description,
                ]);

                foreach($request->products as $product){
                    ProductMovement:: create([
                        'product_id' => $product['product_id'],
                        'store_house_movement_id' => $storeHouseMovementInput->id,
                        'product_movement_type_id' => 1,
                        'quantity' => $product['quantity'],
                    ]);
                }

                $this->saveUserLog($storeHouseMovementInput);

                DB::commit();
    
                return $this->successResponse(StoreHouseMovementResource::collection([$storeHouseMovementOutput,$storeHouseMovementInput]), Response ::HTTP_CREATED);

            }
            else{
                DB::beginTransaction();
            
                $storeHouseMovement = StoreHouseMovement::create($request->toArray());

                foreach($request->products as $product){
                    ProductMovement:: create([
                        'product_id' => $product['product_id'],
                        'store_house_movement_id' => $storeHouseMovement->id,
                        'product_movement_type_id' => $storeHouseMovement->store_house_movement_type_id,
                        'quantity' => $product['quantity'],
                    ]);
                }

                $this->saveUserLog($storeHouseMovement);

                DB::commit();

                return $this->successResponse(new StoreHouseMovementResource($storeHouseMovement), Response ::HTTP_CREATED);
            }
            

        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoreHouseMovement  $storeHouseMovement
     * @return \Illuminate\Http\Response
     */
    public function show(StoreHouseMovement $storeHouseMovement)
    {
        return $this->successResponse(new StoreHouseMovementResource($storeHouseMovement));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreHouseMovement  $storeHouseMovement
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreHouseMovement $storeHouseMovement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreHouseMovement  $storeHouseMovement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreHouseMovement $storeHouseMovement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreHouseMovement  $storeHouseMovement
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreHouseMovement $storeHouseMovement)
    {
        //
    }
}
