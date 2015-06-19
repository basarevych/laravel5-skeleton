<?php

namespace App\Http\Controllers;

use Auth;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Display login form
     *
     * @return Response
     */
    public function getLoginForm()
    {
        return view('auth.login-form');
    }

    /**
     * Process login form
     *
     * @param \App\Http\Requests\LoginRequest $request
     * @return Response
     */
    public function postLoginForm(Requests\LoginRequest $request)
    {
        $attempt = Auth::attempt(
            [
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ],
            $request->input('remember_me') == '1'
        );

        if ($attempt)
            return view('layouts/script', [ 'script' => "$('#modal-form').modal('hide'); window.location.reload()" ]);

        return redirect('auth/login-form')->withInput()
                                          ->with('message', trans('auth.invalid_credentials'));
    }

    /**
     * Validate login form field
     *
     * @return Response
     */
    public function postValidateLoginForm(Request $request)
    {
        $rules = (new Requests\LoginRequest)->rules();
        $field = $request->input('field');
        $form = $request->input('form');

        $validator = Validator::make($form, $rules);
        $messages = $validator->messages()->toArray();
        if (isset($messages[$field]))
            return response()->json([ 'valid' => false, 'errors' => $messages[$field] ]);

        return response()->json([ 'valid' => true, 'errors' => [] ]);
    }

    /**
     * Sign out
     *
     * @return Response
     */
    public function getLogout()
    {
        if (Auth::check())
            Auth::logout();

        return back();
    }
}
