<?php

namespace App\Http\Requests;

use Auth;

use App\Http\Requests\Request;

class ProfileRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
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
            'email'                 => 'required|max:255|email|unique:users,email,' . Auth::user()->id,
            'password'              => 'min:6|max:255|confirmed|required_with:password_confirmation',
            'password_confirmation' => 'min:6|max:255|required_with:password',
        ];
    }
}
