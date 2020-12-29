<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRateCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currency_rates' => 'required|array|min:1',
            'currency_rates.*.currency_id' => 'required|numeric|min:1',
            'currency_rates.*.rate_value' => 'required|numeric|min:0',
        ];
    }
}
