<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyUpdateRequest extends FormRequest
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
            'name' => 'nullable|min:2',
            'plural_name' => 'nullable|min:2',
            'symbol' => 'nullable|min:1',
            'code' => 'nullable|min:2',
            'is_base' => 'nullable|boolean',
        ];
    }
}
