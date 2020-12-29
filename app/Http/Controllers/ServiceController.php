<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceCreateRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
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

        $services = Service::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%");
        })
        ->paginate($paginate);

        return ServiceResource::collection($services);
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
     * @param  \App\Http\Requests\ServiceCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $service = Service::create($request->toArray());
            
            $this->saveUserLog($service);

            DB::commit();
            
            return $this->successResponse(new ServiceResource($service), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return $this->successResponse(new ServiceResource($service));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        try{
            
            DB::beginTransaction();
            
            $service->fill($request->toArray());
            $service->save();
            
            if($request->prices){
                foreach($request->prices as $price){
                    ServicePrice::where('service_id', '=', $service->id)
                    ->where('currency_id', '=', $price['currency_id'])
                    ->withTrashed()
                    ->update([
                        'sale_price' => $price['sale_price'],
                    ]);
                }
            }
        
            $this->saveUserLog($service, 'update');

            DB::commit();
            
            return $this->successResponse(new ServiceResource($service));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service = Service::withTrashed()->findOrFail($service);

        if ($service->trashed()) {
            $service->restore();
            $this->saveUserLog($service, 'restore');
        } else {
            $service->delete();
            $this->saveUserLog($service, 'delete');
        }

        return $this->successResponse(new ServiceResource($service));
    }
}
