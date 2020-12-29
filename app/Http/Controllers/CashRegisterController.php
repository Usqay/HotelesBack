<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashRegisterCreateRequest;
use App\Http\Requests\CashRegisterUpdateRequest;
use App\Http\Resources\CashRegisterResource;
use App\Models\CashRegister;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CashRegisterController extends Controller
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
        $cashRegisters = CashRegister::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%")
                ->orWhere("description", "like", "%$q%")
                ->orWhere("location", "like", "%$q%");
        })
        ->paginate($paginate);
        return CashRegisterResource::collection($cashRegisters);
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
     * @param  \App\http\Requests\CashRegisterCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashRegisterCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $cashRegister = CashRegister::create($request->toArray());
        
            $this->saveUserLog($cashRegister);

            DB::commit();
            
            return $this->successResponse(new CashRegisterResource($cashRegister), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function show(CashRegister $cashRegister)
    {
        return $this->successResponse(new CashRegisterResource($cashRegister));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(CashRegister $cashRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CashRegisterUpdateRequest  $request
     * @param  \App\Models\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function update(CashRegisterUpdateRequest $request, CashRegister $cashRegister)
    {
        try{
            
            DB::beginTransaction();

            
            if($request->is_base){
                CashRegister::where('is_base', '=', 1)
                ->where('id', '!=', $cashRegister->id)
                ->update([
                    'is_base' => 0
                ]);
            }
            
            $cashRegister->fill($request->toArray());
            $cashRegister->save();
        
            $this->saveUserLog($cashRegister, 'update');

            DB::commit();
            
            return $this->successResponse(new CashRegisterResource($cashRegister));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy($cashRegister)
    {
        $cashRegister = CashRegister::withTrashed()->findOrFail($cashRegister);

        if ($cashRegister->trashed()) {
            $cashRegister->restore();
            $this->saveUserLog($cashRegister, 'restore');
        } else {
            $cashRegister->delete();
            $this->saveUserLog($cashRegister, 'delete');
        }

        return $this->successResponse(new CashRegisterResource($cashRegister));
    }
}
