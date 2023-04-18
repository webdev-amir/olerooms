<?php

namespace Modules\Users\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Roles\Entities\Role;

class UpdateAgentRequest extends FormRequest
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
            //'email' => 'required|email|unique:users,email,' . $this->request->get('id'),
            'phone' => "required|numeric",
            'email' => "required|email|regex:/(.+)@(.+)\.(.+)/i",
            'password_confirmation' => 'required_with:password|same:password',
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

    public function withValidator(Validator $validator)
    {
        $role = Role::where('slug', 'agent')->first();
        if (!empty($role)) {
            $role_id = $role->id;
            $email = $validator->getData()['email'] ?? '';
            $phone = $validator->getData()['phone'] ?? '';
            $id = $validator->getData()['id'] ?? '';
            $validator->after(
                function ($validator) use ($email, $role_id, $id, $phone) {
                    if (User::where(['email' => $email, 'role_id' => $role_id])->where('id', '!=', $id)->withTrashed()->first()) {
                        $validator->errors()->add(
                            'email',
                            'Agent email already exists!'
                        );
                    }
                    if (User::where(['phone' => $phone, 'role_id' => $role_id])->where('id', '!=', $id)->withTrashed()->first()) {
                        $validator->errors()->add(
                            'email',
                            'Agent phone number already exists!'
                        );
                    }
                }
            );
        }
    }
}
