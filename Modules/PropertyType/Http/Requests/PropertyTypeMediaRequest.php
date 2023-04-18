<?php

namespace Modules\PropertyType\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyTypeMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         return [
            'files' => 'required|image|mimes:jpeg,jpg,png,svg|max:5120',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
