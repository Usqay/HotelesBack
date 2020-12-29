<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyCreateRequest;
use App\Http\Requests\CurrencyUpdateRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
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
        $currencies = Currency::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%")
                ->orWhere("plural_name", "like", "%$q%")
                ->orWhere("symbol", "like", "%$q%")
                ->orWhere("code", "like", "%$q%");
        })
        ->paginate($paginate);
        return CurrencyResource::collection($currencies);
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
     * @param  \App\http\Requests\CurrencyCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CurrencyCreateRequest $request)
    {
        try{
            
            DB::beginTransaction();
            
            $currency = Currency::create($request->toArray());
        
            $this->saveUserLog($currency);

            DB::commit();
            
            return $this->successResponse(new CurrencyResource($currency), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        return $this->successResponse(new CurrencyResource($currency));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CurrencyUpdateRequest  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(CurrencyUpdateRequest $request, Currency $currency)
    {        
        try{
            
            DB::beginTransaction();

            if($request->is_base){
                Currency::where('is_base', '=', 1)
                ->where('id', '!=', $currency->id)
                ->update([
                    'is_base' => 0
                ]);
            }
            
            $currency->fill($request->toArray());
            $currency->save();
        
            $this->saveUserLog($currency, 'update');

            DB::commit();
            
            return $this->successResponse(new CurrencyResource($currency));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy($currency)
    {
        $currency = Currency::withTrashed()->findOrFail($currency);

        if ($currency->trashed()) {
            $currency->restore();
            $this->saveUserLog($currency, 'restore');
        } else {
            $currency->delete();
            $this->saveUserLog($currency, 'delete');
        }

        return $this->successResponse(new CurrencyResource($currency));
    }
}