<?php

namespace Modules\NewsUpdates\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsUpdatesRequest extends FormRequest
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
            'published_at' => 'after:yesterday',
        ];
    }

    public function messages()
    {
        return [
            'published_at.after' => 'Publish date must be equal or greater than today!',
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
