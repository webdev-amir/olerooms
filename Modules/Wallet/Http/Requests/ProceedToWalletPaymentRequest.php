<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProceedToWalletPaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->request->get('status') == 'send_pin'){
            return [
                'id' => 'required',
                'reference' => 'required',
                'pin' => 'required',
            ];
        } elseif($this->request->get('status') == 'send_otp'){
            return [
                'id' => 'required',
                'reference' => 'required',
                'otp' => 'required',
            ];
        }else{
            return [
                'id' => 'required',
                'number' => 'required',
                'name' => 'required|max:100',
                'expiry' => 'required',
                'cvc' => 'required|numeric|min:3',
            ];
        }
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
