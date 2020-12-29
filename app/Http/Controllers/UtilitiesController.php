<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\SystemConfiguration;
use App\Models\TurnChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class UtilitiesController extends Controller
{
    public function enviroments(){
        $currency = Currency::where('is_base', '=', true)->first();
        $systemConfigurations = SystemConfiguration::all();
        $cashRegister = CashRegister::where('is_base', '=', true)->first();
        $turnChange = TurnChange::where('status_active', '=', true)->first();
        $currenciesCount = Currency::count('id');
        $currencyRates = CurrencyRate::orderBy('rate_date', 'DESC')->limit($currenciesCount - 1)->get();
    
        return $this->successResponse([
            'base_currency' => $currency,
            'system_configurations' => $systemConfigurations,
            'turn_change' => $turnChange,
            'currency_rates' => $currencyRates,
            'cash_register' => $cashRegister,
        ]);
    }

    public function currency_rates($base){
        try{
            $url = env('CURRENCY_RATE_API_URL', 'https://api.cambio.today/v1');
            $key = env('CURRENCY_RATE_API_KEY', '5180|K4TsgyKBj~zN0p*5FXWYAjoDFH^^Bd2_');
            $requestUrl = "$url/full/$base/json?key=$key";

            $response = Http::get($requestUrl);

            return $this->successResponse($response->json());
            
        }catch(\Exception $e){
            try{
                $result = $this->getLastCurrencyRates();
                
                return $this->successResponse($result);
            }catch(\Exception $ee){
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    private function getLastCurrencyRates(){
        $currenciesTotal = Currency::count('id');
        $currencyRates = CurrencyRate::orderBy('rate_date', 'DESC')->limit($currenciesTotal - 1)->get();

        $result = [];

        foreach($currencyRates as $item){
            $result[] = [
                'to' => Currency::findOrFail($item->currency_id)->code,
                'rate_local' => $item->rate_value
            ];
        }

        return [
            'result' => [
                'conversion' => $result
            ]
        ];
    }
}
