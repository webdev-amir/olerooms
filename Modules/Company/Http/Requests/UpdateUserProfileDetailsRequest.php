<?php

namespace Modules\Company\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class UpdateUserProfileDetailsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         return [
            'name'              => ['required', 'string', 'max:50'],
            // 'company_name'      => ['required', 'string', 'max:100'],
            'phone'             => "required|numeric",
            'email'             => "required|email|regex:/(.+)@(.+)\.(.+)/i",
            'state_id'          => "required",
            'map_location'      => "required",
            'lat'               => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'long'              => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ];
    }

    public function messages()
    {
        return [
            'dob.before' => 'Invalid Date of Birth',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $role_id = Auth::user()->role_id;
        $email = $validator->getData()['email'] ?? '';
        $phone = $validator->getData()['phone'] ?? '';
        $id = $validator->getData()['id'] ?? '';
        $validator->after(
            function ($validator) use ($email, $role_id, $id, $phone) {
                if (User::where(['email' => $email, 'role_id' => $role_id])->where('id', '!=', $id)->withTrashed()->first()) {
                    $validator->errors()->add(
                        'email',
                        'Company email already exists!'
                    );
                }
                if (User::where(['phone' => $phone, 'role_id' => $role_id])->where('id', '!=', $id)->withTrashed()->first()) {
                    $validator->errors()->add(
                        'email',
                        'Company phone number already exists!'
                    );
                }
            }
        );
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
