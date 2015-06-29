<?php

namespace App\Http\Requests;

use ReCaptcha;

use App\Http\Requests\Request;

class RegistrationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return config('auth.registration.enable');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                  => 'max:255',
            'email'                 => 'required|max:255|email|unique:users,email',
            'password'              => 'required|min:6|max:255|confirmed',
            'password_confirmation' => 'required|min:6|max:255',
        ];

        if (ReCaptcha::isEnabled())
            $rules['g-recaptcha-response'] = 'required|recaptcha';

        return $rules;
    }
}
