<?php

namespace Modules\Coupon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:100',
            'offer_type' => 'required|in:Flatrate,Percentage',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            //'property_type_id' => 'required',
            'description' => 'required',
            'coupon_code' => 'required|unique:coupon,coupon_code,'.$this->id,
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
