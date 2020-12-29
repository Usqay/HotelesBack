<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashRegisterMovementStoreRequest extends FormRequest
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
            'currency_id' => 'required|numeric|min:1',
            'cash_register_movement_type_id' => 'required|numeric|min:1',
            'cash_register_id' => 'required|numeric|min:1',
            'turn_change_id' => 'required|numeric|min:1',
            'payment_method_id' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ];
    }
}
