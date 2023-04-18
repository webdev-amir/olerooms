<?php

namespace Modules\Contactus\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContactusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:150',
            'email' => 'required|email|max:150',            
            'phone'    => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',      
            'message' => 'required',
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
