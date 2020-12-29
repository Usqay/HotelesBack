<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRoomCreateRequest extends FormRequest
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
            'room_id' => 'required|numeric|min:1',
            'currency_id' => 'required|numeric|min:1',
            'price_type' => 'required|string|in:hour,day',
            'price_value' => 'required|numeric|min:0',
        ];
    }
}
