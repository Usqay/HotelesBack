<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyCreateRequest extends FormRequest
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
            'name' => 'required|min:2|unique:currencies',
            'plural_name' => 'required|min:2',
            'symbol' => 'required|min:1',
            'code' => 'required|min:2',
            'is_base' => 'required|boolean',
        ];
    }
}
