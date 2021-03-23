<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashRegisterMovementStoreRequest;
use App\Http\Resources\CashRegisterMovementResource;
use App\Models\CashRegisterMovement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CashRegisterMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = request()->query('q');
        $cashRegisterId = request()->query('cash_register');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $cashRegisterMovements = CashRegisterMovement::orderBy('id', 'DESC');

        if(isset($cashRegisterId)){
            $cashRegisterMovements->where('cash_register_id', '=', $cashRegisterId);
        }

        $cashRegisterMovements = $cashRegisterMovements->paginate($paginate);

        return CashRegisterMovementResource::collection($cashRegisterMovements);
    }

    public function listar()
    {
        $q = request()->query('q');
        $request =request();
        $cashRegisterId = request()->query('cash_register');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $cashRegisterMovements = CashRegisterMovement::orderBy('id', 'DESC');

        $cashRegisterMovements->whereBetween('created_at',[$request['f_inicio'],$request['f_fin']]);
        if(isset($cashRegisterId)){
            $cashRegisterMovements->where('cash_register_id', '=', $cashRegisterId);
        }

        $cashRegisterMovements = $cashRegisterMovements->paginate($paginate);

        return CashRegisterMovementResource::collection($cashRegisterMovements);
    }


    public function imprimir2()
    {
        $q = request()->query('q');
        $request =request();
        $cashRegisterId = request()->query('cash_register');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $cashRegisterMovements = CashRegisterMovement::orderBy('id', 'DESC');
        $cashRegisterMovements->whereBetween('created_at',[$request['f_inicio'],$request['f_fin']]);
        if(isset($cashRegisterId)){
            $cashRegisterMovements->where('cash_register_id', '=', $cashRegisterId);
        }

        $cashRegisterMovements = $cashRegisterMovements->paginate($paginate);

        $res= CashRegisterMovementResource::collection($cashRegisterMovements);

        $datos = $res->collection->transform(function($page){
            return $page;
        });
       return ['success' => true,'imprimir' => \View::make('reports.movimientos_caja', compact('datos','request'))->render()];
       // return \View::make('reports.movimientos_caja', compact('datos'));
    }

   /* public function imprimir()
    {
        $q = request()->query('q');
        $cashRegisterId = request()->query('cash_register');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $cashRegisterMovements = CashRegisterMovement::orderBy('id', 'DESC');

        if(isset($cashRegisterId)){
            $cashRegisterMovements->where('cash_register_id', '=', $cashRegisterId);
        }

        $cashRegisterMovements = $cashRegisterMovements->paginate($paginate);

        return CashRegisterMovementResource::collection($cashRegisterMovements);

       // $data = $res['data'];
        //return ['success' => true,'imprimir' => \View::make('reports.movimientos_caja', compact('data'))];
    }*/



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
    public function store(CashRegisterMovementStoreRequest $request)
    {
        try{

            DB::beginTransaction();

            $data = $request->toArray();
            $data['user_id'] = auth()->user()->id;

            $cashRegisterMovement = CashRegisterMovement::create($data);

            $this->saveUserLog($cashRegisterMovement);

            DB::commit();

            return $this->successResponse(new CashRegisterMovementResource($cashRegisterMovement), Response::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CashRegisterMovement  $cashRegisterMovement
     * @return \Illuminate\Http\Response
     */
    public function show(CashRegisterMovement $cashRegisterMovement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CashRegisterMovement  $cashRegisterMovement
     * @return \Illuminate\Http\Response
     */
    public function edit(CashRegisterMovement $cashRegisterMovement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CashRegisterMovement  $cashRegisterMovement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CashRegisterMovement $cashRegisterMovement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CashRegisterMovement  $cashRegisterMovement
     * @return \Illuminate\Http\Response
     */
    public function destroy(CashRegisterMovement $cashRegisterMovement)
    {
        //
    }
}
