<?php

namespace App\Http\Controllers;

use App\Http\Requests\TurnCreateRequest;
use App\Http\Requests\TurnUpdateRequest;
use App\Http\Resources\TurnResource;
use App\Models\Turn;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TurnController extends Controller
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

        $turns = Turn::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%");
        })
        ->paginate($paginate);

        return TurnResource::collection($turns);
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
     * @param  \App\Http\Requests\TurnCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TurnCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $turn = Turn::create($request->toArray());
            
            $this->saveUserLog($turn);

            DB::commit();
            
            return $this->successResponse(new TurnResource($turn), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Turn  $turn
     * @return \Illuminate\Http\Response
     */
    public function show(Turn $turn)
    {
        return $this->successResponse(new TurnResource($turn));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Turn  $turn
     * @return \Illuminate\Http\Response
     */
    public function edit(Turn $turn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TurnUpdateRequest  $request
     * @param  \App\Models\Turn  $turn
     * @return \Illuminate\Http\Response
     */
    public function update(TurnUpdateRequest $request, Turn $turn)
    {
        try{
            
            DB::beginTransaction();
            
            $turn->fill($request->toArray());
            $turn->save();
        
            $this->saveUserLog($turn, 'update');

            DB::commit();
            
            return $this->successResponse(new TurnResource($turn));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $turn
     * @return \Illuminate\Http\Response
     */
    public function destroy($turn)
    {
        $turn = Turn::withTrashed()->findOrFail($turn);

        if ($turn->trashed()) {
            $turn->restore();
            $this->saveUserLog($turn, 'restore');
        } else {
            $turn->delete();
            $this->saveUserLog($turn, 'delete');
        }

        return $this->successResponse(new TurnResource($turn));
    }
}
