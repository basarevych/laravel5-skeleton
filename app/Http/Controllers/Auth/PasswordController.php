<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Mail;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;

use App\User;
use App\Token;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Contracts\Repositories\Users;
use App\Contracts\Repositories\Tokens;

class PasswordController extends Controller
{
    /**
     * Users repository
     *
     * @var Users
     */
    protected $users;

    /**
     * Tokens repository
     *
     * @var Tokens
     */
    protected $tokens;

    /**
     * Constructor
     *
     * @param Users $users
     * @param Tokens $tokens
     */
    public function __construct(Users $users, Tokens $tokens)
    {
        $this->users = $users;
        $this->tokens = $tokens;
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

        $token = $this->tokens->create($user, Token::TYPE_PASSWORD_RESET);

        Mail::send(
            [ 'html' => config('auth.password.email') ],
            [
                'user'  => $user,
                'token' => $token,
            ],
            function ($message) use ($user)
            {
                $message->to($user->email, $user->name)
                        ->subject(trans('password.email_title'));
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
        $expired = true;
        $model = $this->tokens->findByToken($token);
        if ($model && $model->type == Token::TYPE_PASSWORD_RESET)
            $expired = false;

        return view('password.reset-confirm', [ 'token' => $token, 'expired' => $expired ]);
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
        $token = $this->tokens->findByToken($request->input('reset_token'));
        if (!$token || $token->type != Token::TYPE_PASSWORD_RESET) {
            return redirect('auth/reset-confirm-form/' . $request->input('reset_token'))
                      ->withInput()
                      ->with('message', trans('password.invalid_token'));
        }

        $user = $token->user()->first();
        $user->password = bcrypt($request->input('password'));
        $user->save();
        $token->delete();

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
