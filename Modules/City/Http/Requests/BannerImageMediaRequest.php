<?php

namespace Modules\City\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerImageMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'files' => 'required|image|mimes:jpeg,png,jpg,svg|max:5048',
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
