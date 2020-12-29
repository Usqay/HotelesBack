<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomProductUpdateRequest extends FormRequest
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
            'product_id' => 'nullable|numeric|min:1|exists:products,id',
            'quantity' => 'nullable|numeric|min:1',
        ];
    }
}
