<?php

namespace App\Http\Requests;

use Auth;

use App\Http\Requests\Request;

class CreateUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'max:255',
            'email'                 => 'required|max:255|email|unique:users,email',
            'password'              => 'required|min:6|max:255|confirmed',
            'password_confirmation' => 'required|min:6|max:255',
            'is_active'             => '',
            'is_admin'              => '',
        ];
    }
}
