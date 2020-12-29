<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationUpdateRequest extends FormRequest
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
            'start_date' => 'nullable',
            'end_date' => 'nullable',
            'client_id' => 'nullable|numeric|min:1',
            'reservation_origin_id' => 'nullable|numeric|min:1',
            'reservation_state_id' => 'nullable|numeric|min:1',
            'description' => 'nullable|string|min:2',
        ];
    }
}
