<?php

namespace Modules\MyDashboard\Http\Requests;

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
            'name' => ['required', 'string', 'max:50'],
            'city' =>  ['required'],
            'phone' => "required|numeric",
            'gender' =>  'required|in:male,female,other',
            'dob' =>  'required|before:today',
            'email' => "required|email|regex:/(.+)@(.+)\.(.+)/i",
            'occupation' => "required",
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
                        'Customer email already exists!'
                    );
                }
                if (User::where(['phone' => $phone, 'role_id' => $role_id])->where('id', '!=', $id)->withTrashed()->first()) {
                    $validator->errors()->add(
                        'email',
                        'Customer phone number already exists!'
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
