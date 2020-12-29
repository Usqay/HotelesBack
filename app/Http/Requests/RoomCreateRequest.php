<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomCreateRequest extends FormRequest
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
            'name' => 'required|string|min:1',
            'description' => 'nullable|string',
            'capacity' => 'required|numeric|min:1',
            'room_category_id' => 'required|numeric|exists:room_categories,id',
            'room_status_id' => 'required|numeric|exists:room_statuses,id',
        ];
    }
}
