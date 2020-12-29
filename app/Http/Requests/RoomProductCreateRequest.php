<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomProductCreateRequest extends FormRequest
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
            'room_id' => 'required|numeric|min:1',
            'product_id' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
        ];
    }
}
