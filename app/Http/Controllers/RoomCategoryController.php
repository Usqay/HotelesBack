<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomCategoryCreateRequest;
use App\Http\Requests\RoomCategoryUpdateRequest;
use App\Http\Resources\RoomCategoryResource;
use App\Models\RoomCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoomCategoryController extends Controller
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

        $roomCategories = RoomCategory::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%");
        })
        ->paginate($paginate);

        return RoomCategoryResource::collection($roomCategories);
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
     * @param  \App\Http\Requests\RoomCategoryCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomCategoryCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $roomCategory = RoomCategory::create($request->toArray());
        
            $this->saveUserLog($roomCategory);

            DB::commit();
            
            return $this->successResponse($roomCategory, Response::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoomCategory  $roomCategory
     * @return \Illuminate\Http\Response
     */
    public function show(RoomCategory $roomCategory)
    {
        return $this->successResponse(new RoomCategoryResource($roomCategory));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $roomCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(RoomCategory $roomCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RoomCategoryUpdateRequest  $request
     * @param  \App\Models\RoomCategory  $roomCategory
     * @return \Illuminate\Http\Response
     */
    public function update(RoomCategoryUpdateRequest $request, RoomCategory $roomCategory)
    {
        try{
            
            DB::beginTransaction();
            
            $roomCategory->fill($request->toArray());
            $roomCategory->save();
        
            $this->saveUserLog($roomCategory, 'update');

            DB::commit();
            
            return $this->successResponse(new RoomCategoryResource($roomCategory), Response::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $roomCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($roomCategory)
    {
        $roomCategory = RoomCategory::withTrashed()->findOrFail($roomCategory);

        if ($roomCategory->trashed()) {
            $roomCategory->restore();
            $this->saveUserLog($roomCategory, 'restore');
        } else {
            $roomCategory->delete();
            $this->saveUserLog($roomCategory, 'delete');
        }

        return $this->successResponse(new RoomCategoryResource($roomCategory));
    }
}