<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Contracts\Repositories\Users;

class AuthController extends Controller
{
    /**
     * Users repository
     *
     * @var Users
     */
    protected $users;

    /**
     * Constructor
     *
     * @param Users $users
     */
    public function __construct(Users $users)
    {
        $this->users = $users;
    }

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
        $user = $this->users->findByEmail($request->input('email'));
        if ($user && !$user->is_active) {
            return redirect('auth/login-form')->withInput()
                                              ->with('message', trans('auth.user_disabled'));
        }

        $attempt = Auth::attempt(
            [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'is_active' => true,
            ],
            $request->input('remember_me') == '1'
        );

        if ($attempt) {
            return view(
                'layouts/script',
                [ 'script' => "$('#modal-form').modal('hide'); window.location.reload()" ]
            );
        }

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
