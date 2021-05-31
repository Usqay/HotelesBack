<?php

namespace App\Traits;
use App\Models\Currency;
use App\Models\SystemConfiguration;
use App\Models\CashRegister;
use App\Models\TurnChange;
use App\Models\CurrencyRate;


trait Utilities {


    public function enviroments(){
        $currency = Currency::where('is_base', '=', true)->first();
        $systemConfigurations = SystemConfiguration::get();
        $cashRegister = CashRegister::where('is_base', '=', true)->first();
        $turnChange = TurnChange::where('status_active', '=', true)->first();
        $currenciesCount = Currency::count('id');
        $currencyRates = CurrencyRate::orderBy('rate_date', 'DESC')->limit($currenciesCount - 1)->get();
      

        return [
            'base_currency' => $currency,
            'system_configurations' => $systemConfigurations,
            'turn_change' => $turnChange,
            'currency_rates' => $currencyRates,
            'cash_register' => $cashRegister,
        ];
    }


}
