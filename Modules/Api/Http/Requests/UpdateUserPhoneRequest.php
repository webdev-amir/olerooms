<?php

namespace Modules\Api\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateUserPhoneRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => "required|numeric",
        ];
    }

    public function messages()
    {
        return [
           //
        ];
    }

    public function withValidator(Validator $validator)
    {
        $role_id = auth()->user()->role_id;
        $phone = $validator->getData()['phone'] ?? '';
        $id = $validator->getData()['id'] ?? '';
        $validator->after(
            function ($validator) use ($role_id, $id, $phone) {
                if (User::where(['phone' => $phone, 'role_id' => $role_id])->where('id', '!=',auth()->user()->id)->withTrashed()->first()) {
                    $validator->errors()->add(
                        'email',
                        'Customer phone number already exists!'
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
