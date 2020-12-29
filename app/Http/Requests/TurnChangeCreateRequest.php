<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TurnChangeCreateRequest extends FormRequest
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
            'turn_change_id' => 'nullable|numeric|min:1',
            'turn_id' => 'required|numeric|min:1',
            'cash_register_id' => 'required|numeric|min:1',
            'start_amount' => 'required|numeric|min:0',

            'currency_rates' => 'nullable|array|min:0',
            'currency_rates.*.currency_id' => 'required|numeric|min:1',
            'currency_rates.*.rate_value' => 'required|numeric|min:0',
            'currency_rates.*.start_amount' => 'required|numeric|min:0',
        ];
    }
}
