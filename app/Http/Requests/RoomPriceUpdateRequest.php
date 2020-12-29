<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomPriceUpdateRequest extends FormRequest
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
            'room_id' => 'nullable|numeric|min:1|exists:rooms,id',
            'currency_id' => 'nullable|numeric|min:1|exists:currencies,id',
            'day_price' => 'nullable|numeric|min:0',
            'hour_price' => 'nullable|numeric|min:0',
        ];
    }
}
