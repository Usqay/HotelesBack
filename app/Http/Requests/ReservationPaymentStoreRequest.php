<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationPaymentStoreRequest extends FormRequest
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
            'reservation_id' => 'required|numeric|min:1',
            'description' => 'nullable|string|min:2',
            'payment_method_id' => 'required|numeric|min:1',
            'people_id' => 'nullable|numeric|min:1',
            'total' => 'required|numeric|min:1',
            'payment_back' => 'nullable|numeric',
            'payment_by' => 'nullable|numeric|min:0',
            'document_type' => 'required|in:bol,fac,not',
            'cash_register_id' => 'required|numeric|min:1',
            'print_payment' => 'required',
        ];
    }
}
