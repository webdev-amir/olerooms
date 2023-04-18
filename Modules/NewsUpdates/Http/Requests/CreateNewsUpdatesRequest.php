<?php

namespace Modules\NewsUpdates\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewsUpdatesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array 
     */
    public function rules()
    {
        return [
            'title' => 'required|max:150',
            'published_at' => 'after:yesterday'
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
