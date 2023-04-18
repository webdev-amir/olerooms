<?php

namespace Modules\PropertyOwnerDashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadAgreementMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         return [
            'files' => 'required|mimes:jpeg,jpg,png,svg,pdf|max:5120',
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
