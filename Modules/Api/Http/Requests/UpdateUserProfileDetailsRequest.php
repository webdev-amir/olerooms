<?php

namespace Modules\Api\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;



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
            'gender' =>  'in:male,female,other',
            'dob' =>  'before:today',
            'email' => "required|email|regex:/(.+)@(.+)\.(.+)/i",
        ];
    }

    public function messages()
    {
        return [
            'dob.before' => 'Invalid Date of Birth.please enter date after today',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $role_id = auth()->user()->role_id;
        $email = $validator->getData()['email'] ?? '';
        $id = $validator->getData()['id'] ?? '';
        $validator->after(
            function ($validator) use ($email, $role_id, $id) {
                if (User::where(['email' => $email, 'role_id' => $role_id])->where('id', '!=', auth()->user()->id)->withTrashed()->first()) {
                    $validator->errors()->add(
                        'email',
                        'Customer email already exists!'
                    );
                }
            }
        );
    }
    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422)); 
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
