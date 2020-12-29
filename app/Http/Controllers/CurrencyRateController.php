<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CurrencyRateController extends Controller
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
     * @param  \App\Http\Requests\CurrencyRateCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CurrencyRateCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            $date = date('Y-m-d');
            
            foreach($request->currency_rates as $item){
                CurrencyRate::updateOrCreate([
                    'rate_date' => $date,
                    'currency_id' => $item['currency_id'],
                ], [
                    'rate_value' => $item['rate_value'],
                ]);
            }

            DB::commit();
            
            return $this->successResponse([success => true], Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CurrencyRate  $currencyRate
     * @return \Illuminate\Http\Response
     */
    public function show(CurrencyRate $currencyRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CurrencyRate  $currencyRate
     * @return \Illuminate\Http\Response
     */
    public function edit(CurrencyRate $currencyRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CurrencyRate  $currencyRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CurrencyRate $currencyRate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CurrencyRate  $currencyRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(CurrencyRate $currencyRate)
    {
        //
    }
}
