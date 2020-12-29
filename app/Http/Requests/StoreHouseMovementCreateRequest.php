<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHouseMovementCreateRequest extends FormRequest
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
            'store_house_id' => 'required|exists:store_houses,id|numeric|min:1',
            'second_store_house_id' => 'nullable|exists:store_houses,id|numeric|min:1',
            'store_house_movement_type_id' => 'required|exists:store_house_movement_types,id|min:1',
            'description' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|numeric|min:1',
            'products.*.quantity' => 'required|numeric|min:0.1',
        ];
    }
}
