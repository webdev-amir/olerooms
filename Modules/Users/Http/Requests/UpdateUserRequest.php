<?php

namespace Modules\Users\Http\Requests;

use App\Models\User;
use Modules\Roles\Entities\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'name' => ['required', 'string', 'max:255'],
            //'email' => 'required|email|unique:users,email,' . $this->request->get('id'),
            'password_confirmation' => 'required_with:password|same:password',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    public function withValidator(Validator $validator) {
        $role = Role::where('slug', 'customer')->first();
        if (!empty($role)) {
            $role_id = $role->id;
            $email = $validator->getData()['email'] ?? '';
            $phone = $validator->getData()['phone'] ?? '';
            $id = $validator->getData()['id'] ?? '';
            $validator->after(
                    function ($validator) use ($email, $role_id, $id, $phone) {
                        if (User::where(['email' => $email, 'role_id' => $role_id])
                                        ->where('id', '!=', $id)
                                        ->withTrashed()
                                        ->first()) {
                            $validator->errors()->add(
                                    'email',
                                    'User email already exists!'
                            );
                        }
                        if (User::where(['phone' => $phone, 'role_id' => $role_id])
                                        ->where('id', '!=', $id)
                                        ->withTrashed()
                                        ->first()) {
                            $validator->errors()->add(
                                    'email',
                                    'User phone number already exists!'
                            );
                        }
                    }
            );
        }
    }

}
