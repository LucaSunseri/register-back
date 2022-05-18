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
            'name' => 'required|string|min:2|max:255',
            'surname' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email|min:4|max:255',
            'password' => 'required|string|confirmed|min:6|max:255',
        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => 'Password non combacia',
        ];
    }
}
