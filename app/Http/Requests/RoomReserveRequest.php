<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomReserveRequest extends FormRequest
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
            'cash_register_id' => 'required|numeric|min:1',
            'create_payment' => 'required|boolean',
            'days' =>  'required|numeric',
            'end_date' =>  "required",
            'hours' =>  'required|numeric',
            'payment_amount' =>  'required|numeric',
            'payment_back' =>  'required|numeric',
            'payment_document_type' =>  "required|in:not,bol,fac",
            'payment_method_id' =>  'required|numeric',
            'payment_total' =>  'required|numeric',
            'people_document_number' =>  "required",
            'people_document_type_id' =>  'required|numeric',
            'people_full_name' =>  "nullable",
            'people_gender_id' =>  'required|numeric',
            'people_last_name' =>  "nullable",
            'people_name' =>  "nullable",
            'print_payment' =>  'required|boolean',
            'mark_checking' =>  'required|boolean',
            'rate_value' =>  'required|numeric',
            'room_id' =>  'required|numeric',
            'room_price_currency_id' =>  'required|numeric',
            'room_price_type' =>  "required|in:day,hour",
            'room_price_value' =>  "required|numeric",
            'start_date' =>  "required",
        ];
    }
}
