<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleStoreRequest extends FormRequest
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
            'products' => 'array',
            'services' => 'array',
            'totals' => 'required|array|min:1',
            
            'people.address' => 'nullable|min:2',
            'people.birthday_date' => 'nullable|min:2',
            'people.document_number' => 'nullable|string|min:8|max:20',
            'people.document_type_id' => 'nullable|min:1',
            'people.email' => 'nullable|email',
            'people.full_name' => 'nullable|min:2',
            'people.gender_id' => 'nullable|min:1',
            'people.last_name' => 'nullable|min:2',
            'people.name' => 'nullable|min:2',
            'people.phone_number' => 'nullable|string|min:6',
        ];
    }
}

