<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Mail;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Contracts\Repositories\PasswordResets;
use App\Contracts\Repositories\Users;

class PasswordController extends Controller
{
    /**
     * Users repository
     *
     * @var Users
     */
    protected $users;

    /**
     * Password resets repository
     *
     * @var PasswordResets
     */
    protected $passwordResets;

    /**
     * Constructor
     *
     * @param Users $users
     * @param PasswordResets $passwordResets
     */
    public function __construct(Users $users, PasswordResets $passwordResets)
    {
        $this->users = $users;
        $this->passwordResets = $passwordResets;
    }

    /**
     * Display password reset request form
     *
     * @return Response
     */
    public function getResetRequestForm()
    {
        return view('password.reset-request-form');
    }

    /**
     * Process password reset request form
     *
     * @param \App\Http\Requests\RequestPasswordResetRequest $request
     * @return Response
     */
    public function postResetRequestForm(Requests\RequestPasswordResetRequest $request)
    {
        $user = $this->users->findByEmail($request->input('email'));
        if (!$user) {
            return redirect('auth/reset-request-form')->withInput()
                                                      ->with('message', trans('password.invalid_user'));
        }

        $this->passwordResets->deleteExpired();

        $reset = $this->passwordResets->create($user);

        Mail::send(
            [ 'html' => 'emails.password' ],
            [
                'user'  => $user,
                'reset' => $reset,
            ],
            function ($message) use ($user)
            {
                $message->to($user->email, $user->name)
                        ->subject(trans('password.request_email_title'));
            }
        );

        $msg = trans('password.email_sent_text');
        $title = trans('password.email_sent_title');
        return view(
            'layouts/script',
            [ 'script' => "$('#modal-form').modal('hide'); bsAlert(\"$msg\", \"$title\")" ]
        );
    }

    /**
     * Validate password reset request form field
     *
     * @return Response
     */
    public function postValidateRequestForm(Request $request)
    {
        $rules = (new Requests\RequestPasswordResetRequest)->rules();
        $field = $request->input('field');
        $form = $request->input('form');

        $validator = Validator::make($form, $rules);
        $messages = $validator->messages()->toArray();
        if (isset($messages[$field]))
            return response()->json([ 'valid' => false, 'errors' => $messages[$field] ]);

        return response()->json([ 'valid' => true, 'errors' => [] ]);
    }

    /**
     * Display password reset confirmation page
     *
     * @return Response
     */
    public function getResetConfirm($token)
    {
        $reset = $this->passwordResets->findByToken($token);
        return view('password.reset-confirm', [ 'token' => $token, 'expired' => ($reset == null) ]);
    }

    /**
     * Display password reset confirmation form
     *
     * @return Response
     */
    public function getResetConfirmForm($token)
    {
        return view('password.reset-confirm-form', [ 'token' => $token ]);
    }

    /**
     * Process password reset confirmation form
     *
     * @param \App\Http\Requests\ConfirmPasswordResetRequest $request
     * @return Response
     */
    public function postResetConfirmForm(Requests\ConfirmPasswordResetRequest $request)
    {
        $this->passwordResets->deleteExpired();

        $reset = $this->passwordResets->findByToken($request->input('reset_token'));
        if (!$reset) {
            return redirect('auth/reset-confirm-form/' . $request->input('reset_token'))
                      ->withInput()
                      ->with('message', trans('password.invalid_token'));
        }

        $user = $reset->user()->first();
        $user->password = bcrypt($request->input('password'));
        $user->save();
        $reset->delete();

        Auth::login($user);

        return view('layouts/script', [ 'script' => "$('#modal-form').modal('hide'); window.location = '" . url('/') . "'" ]);
    }

    /**
     * Validate password reset confirmation form field
     *
     * @return Response
     */
    public function postValidateConfirmForm(Request $request)
    {
        $rules = (new Requests\ConfirmPasswordResetRequest)->rules();
        $field = $request->input('field');
        $form = $request->input('form');

        $validator = Validator::make($form, $rules);
        $messages = $validator->messages()->toArray();
        if (isset($messages[$field]))
            return response()->json([ 'valid' => false, 'errors' => $messages[$field] ]);

        return response()->json([ 'valid' => true, 'errors' => [] ]);
    }
}
