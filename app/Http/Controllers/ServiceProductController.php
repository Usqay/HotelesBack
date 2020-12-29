<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceProductCreateRequest;
use App\Http\Requests\ServiceProductUpdateRequest;
use App\Http\Resources\ServiceProductResource;
use App\Models\ServiceProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ServiceProductController extends Controller
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
     * @param  \App\Http\Requests\ServiceProductCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceProductCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $serviceProduct = ServiceProduct::updateOrCreate(
                ['service_id' => $request->service_id, 'product_id' => $request->product_id],
                ['quantity' => $request->quantity]
            );

            if($serviceProduct->wasChanged()){
                $this->saveUserLog($serviceProduct, 'update');
            }else{
                $this->saveUserLog($serviceProduct);
            }
        
            DB::commit();
            
            return $this->successResponse(new ServiceProductResource($serviceProduct), Response::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceProduct  $serviceProduct
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceProduct $serviceProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceProduct  $serviceProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceProduct $serviceProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ServiceProductUpdateRequest  $request
     * @param  \App\Models\ServiceProduct  $serviceProduct
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceProductUpdateRequest $request, ServiceProduct $serviceProduct)
    {
        try{
            
            DB::beginTransaction();
            
            $serviceProduct->fill($request->toArray());
            $serviceProduct->save();
        
            $this->saveUserLog($serviceProduct, 'update');

            DB::commit();
            
            return $this->successResponse(new ServiceProductResource($serviceProduct));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceProduct  $serviceProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy($serviceProduct)
    {
        $serviceProduct = ServiceProduct::withTrashed()->findOrFail($serviceProduct);

        if ($serviceProduct->trashed()) {
            $serviceProduct->restore();
            $this->saveUserLog($serviceProduct, 'restore');
        } else {
            $serviceProduct->delete();
            $this->saveUserLog($serviceProduct, 'delete');
        }

        return $this->successResponse(new ServiceProductResource($serviceProduct));
    }
}
