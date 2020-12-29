<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::orderBy('id', 'DESC');

        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        if(!auth()->user() || !auth()->user()->hasRole('Staff')){
            $roles->where('name', '!=', 'Staff');
        }
        $roles = $roles->paginate($paginate);

        return RolResource::collection($roles);
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
        try{
            
            DB::beginTransaction();
            
            $role = Role::create($request->toArray());
        
            $this->saveUserLog($role);

            DB::commit();
            
            return $this->successResponse($role, Response::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data".$e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return $this->successResponse(new RolResource($role));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        try{
            
            DB::beginTransaction();
            
            $role->fill($request->toArray());
            $role->save();
        
            $this->saveUserLog($role, 'update');

            DB::commit();
            
            return $this->successResponse(new RolResource($role), Response::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($role)
    {
        $role = Role::findOrFail($role);

        $role->delete();
        
        return $this->successResponse(['success' => true]);
    }
}
