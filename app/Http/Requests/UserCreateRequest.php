<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'name' => 'required|min:2|string',
            'last_name' => 'required|min:2|string',
            'gender_id' => 'required|min:1|numeric',
            'role_id' => 'required|min:1|numeric',
            'document_type_id' => 'required|min:1|numeric',
            'document_number' => 'required|min:1|numeric',
            'address' => 'nullable|min:2|string',
            'phone_number' => 'nullable|min:2|string',
            'email' => 'required|min:2|string',
            'password' => 'required|size:8|string',
            'birthday_date' => 'nullable|date',
        ];
    }
}
