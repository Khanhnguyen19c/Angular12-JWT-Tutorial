<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'fname' => 'required|string|between:2,100',
            'lname' => 'required|string|between:2,100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'phone' => 'required|numeric',
            'images' => 'max:5024',
        ];
    }
}
