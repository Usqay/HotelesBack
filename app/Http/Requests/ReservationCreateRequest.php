<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationCreateRequest extends FormRequest
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
            'start_date' => 'required',
            'end_date' => 'required',
            'client_id' => 'nullable|numeric|min:1',
            'reservation_origin_id' => 'required|numeric|exists:reservation_origins,id',
            'rooms' => 'required|array|min:1',
            'guests' => 'required|array|min:1',
            'end_prices' => 'required|array|min:1',
        ];
    }
}
