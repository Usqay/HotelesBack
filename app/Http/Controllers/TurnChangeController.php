<?php

namespace App\Http\Controllers;

use App\Http\Requests\TurnChangeCreateRequest;
use App\Models\CashRegisterMovement;
use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\TurnChange;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TurnChangeController extends Controller
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
     * @param  \App\Http\Requests\TurnChangeCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TurnChangeCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();


            $user_id = auth()->user()->id; 
            
            $currencyBase = Currency::where('is_base', '=', true)->first();

            TurnChange::where('status_active', '=', true)->update([
                'status_active' => false,
                'close_date' => date('Y-m-d H:i:s')
            ]);
            
            $turnChange = TurnChange::create([
                'turn_id' => $request->turn_id,
                'open_by_user_id' => $user_id,
                'currency_base_id' => $currencyBase->id,
                'open_date' => date('Y-m-d H:i:s')
            ]);

            CashRegisterMovement::updateOrCreate([
                'currency_id' => $currencyBase->id,
                'turn_change_id' => $turnChange->id,
                'cash_register_id' => $request->cash_register_id,
                'cash_register_movement_type_id' => 1,
            ],[
                'user_id' => $user_id,
                'amount' => $request->start_amount,
                'description' => 'Ingreso de monto inicial',
            ]);

            foreach($request->currency_rates as $item){
                CurrencyRate::updateOrCreate([
                    'rate_date' => date('Y-m-d'),
                    'currency_id' => $item['currency_id'],
                ], [
                    'rate_value' => $item['rate_value'],
                ]);
                
                CashRegisterMovement::updateOrCreate([
                    'currency_id' => $item['currency_id'],
                    'cash_register_id' => $request->cash_register_id,
                    'turn_change_id' => $turnChange->id,
                    'cash_register_movement_type_id' => 1,
                ],[
                    'user_id' => $user_id,
                    'amount' => $item['start_amount'],
                    'description' => 'Ingreso de monto inicial',
                ]);
            }
        
            $this->saveUserLog($turnChange);

            DB::commit();
            
            return $this->successResponse($turnChange, Response::HTTP_OK);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TurnChange  $turnChange
     * @return \Illuminate\Http\Response
     */
    public function show(TurnChange $turnChange)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TurnChange  $turnChange
     * @return \Illuminate\Http\Response
     */
    public function edit(TurnChange $turnChange)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TurnChange  $turnChange
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnChange $turnChange)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TurnChange  $turnChange
     * @return \Illuminate\Http\Response
     */
    public function destroy(TurnChange $turnChange)
    {
        //
    }
}
