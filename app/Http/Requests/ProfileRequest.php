<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'min:2|max:30|string',
            'nickname' => 'min:2|max:20|string|nullable',
            'password' => 'min:8|max:50|string|nullable',
            'bio' => 'min:5|max:100|string|nullable',
            'image' => 'image|nullable',
        ];
    }
}
