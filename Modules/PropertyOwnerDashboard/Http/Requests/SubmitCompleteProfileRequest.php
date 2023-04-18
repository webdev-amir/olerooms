<?php

namespace Modules\PropertyOwnerDashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCompleteProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'aadhar_card_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:12|max:12',
            'gst_number' => 'nullable|min:15|max:15',
            //'adhar_card_doc' => 'required',
            //'selfy_image' => 'required',
            //'logo_image' => 'required',
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
