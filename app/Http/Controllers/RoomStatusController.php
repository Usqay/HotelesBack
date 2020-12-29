<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomStatusCreateRequest;
use App\Http\Requests\RoomStatusUpdateRequest;
use App\Http\Resources\RoomStatusResource;
use App\Models\RoomStatus;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoomStatusController extends Controller
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

        $roomStatuses = RoomStatus::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%");
        })
        ->paginate($paginate);

        return RoomStatusResource::collection($roomStatuses);
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
     * @param  \App\Http\Requests\RoomStatusCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomStatusCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $roomStatus = RoomStatus::create($request->toArray());
        
            $this->saveUserLog($roomStatus);

            DB::commit();
            
            return $this->successResponse(new RoomStatusResource($roomStatus), Response::HTTP_OK);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoomStatus  $roomStatus
     * @return \Illuminate\Http\Response
     */
    public function show(RoomStatus $roomStatus)
    {
        return $this->successResponse(new RoomStatusResource($roomStatus));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $roomStatus
     * @return \Illuminate\Http\Response
     */
    public function edit($roomStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RoomStatusCreateRequest  $request
     * @param  \App\Models\RoomStatus  $roomStatus
     * @return \Illuminate\Http\Response
     */
    public function update(RoomStatusUpdateRequest $request, RoomStatus $roomStatus)
    {        
        try{
            
            DB::beginTransaction();
            
            $roomStatus->fill($request->toArray());
            $roomStatus->save();
        
            $this->saveUserLog($roomStatus, 'update');

            DB::commit();
            
            return $this->successResponse(new RoomStatusResource($roomStatus), Response::HTTP_OK);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $roomStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($roomStatus)
    {
        $roomStatus = RoomStatus::withTrashed()->findOrFail($roomStatus);

        if ($roomStatus->trashed()) {
            $roomStatus->restore();
            $this->saveUserLog($roomStatus, 'restore');
        } else {
            $roomStatus->delete();
            $this->saveUserLog($roomStatus, 'delete');
        }

        return $this->successResponse(new RoomStatusResource($roomStatus));
    }
}