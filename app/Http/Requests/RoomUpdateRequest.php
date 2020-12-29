<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomUpdateRequest extends FormRequest
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
            'name' => 'nullable|string|min:1',
            'description' => 'nullable|string',
            'capacity' => 'nullable|numeric|min:1',
            'room_category_id' => 'nullable|numeric|exists:room_categories,id',
            'room_status_id' => 'nullable|numeric|exists:room_statuses,id',
            'room_prices' => 'nullable|array'
        ];
    }
}