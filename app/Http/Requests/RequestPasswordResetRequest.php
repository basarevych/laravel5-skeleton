<?php

namespace App\Http\Requests;

use ReCaptcha;

use App\Http\Requests\Request;

class RequestPasswordResetRequest extends Request
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
        $rules = [
            'email'     => 'required|max:255|email|exists:users,email',
        ];

        if (ReCaptcha::isEnabled())
            $rules['g-recaptcha-response'] = 'required|recaptcha';

        return $rules;
    }
}
