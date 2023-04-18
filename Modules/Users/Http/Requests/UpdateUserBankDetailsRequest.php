<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserBankDetailsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_number' => 'required|numeric|regex:/^\d{9,18}$/',
            'holder_name'    => 'required|max:50',
            'bank_name'      => 'required|max:50',
            'ifsc_code'      => 'required|regex:/^[A-Za-z]{4}[0-9]{6,7}$/',
        ];
    }

    public function messages()
    {
        return [
            'account_number.regex' => 'Please enter valid account number',
            'ifsc_code.regex' => 'Please enter valid ifsc code',
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
