<?php

namespace Modules\Teams\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  => 'required|max:50',
            'linkedin_url' => ['nullable','regex:/((http?|https)\:\/\/)?([a-zA-Z]+)\.linkedin.com\/[a-z]{2}\/[a-zA-Z0-9]{5,30}/i']
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
