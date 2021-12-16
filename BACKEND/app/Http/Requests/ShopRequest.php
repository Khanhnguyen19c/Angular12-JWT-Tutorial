<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
            'shopname' => 'string|between:2,100',
            'hotline' => 'numeric',
            'address' => 'string|max:255',
            'images' => 'max:5024',
            'newimages' => 'max:5024',
        ];
    }
}
