<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Mail;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use App\Token;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Contracts\Repositories\Users;
use App\Contracts\Repositories\Tokens;

class RegistrationController extends Controller
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
     * Activate user account
     *
     * @param  string $token
     * @return Response
     */
    public function getRegistration($token)
    {
        $expired = true;
        $model = $this->tokens->findByToken($token);
        if ($model && $model->type == Token::TYPE_REGISTRATION) {
            $expired = false;

            $user = $model->user()->first();
            $user->is_active = true;
            $user->save();

            $model->delete();

            Auth::login($user);
        }

        return view('registration.activate', [ 'token' => $token, 'expired' => $expired ]);
    }

    /**
     * Display registration form
     *
     * @return Response
     */
    public function getRegistrationForm()
    {
        return view('registration.registration-form');
    }

    /**
     * Process registration form
     *
     * @param \App\Http\Requests\RegistrationRequest $request
     * @return Response
     */
    public function postRegistrationForm(Requests\RegistrationRequest $request)
    {
        if (!config('auth.registration.confirm')) {
            $user = $this->users->create($request->input(), true, false);
            Auth::login($user);

            $msg = trans('registration.welcome');
            $title = trans('registration.form_title');

            return view(
                'layouts/script',
                [ 'script' => "$('#modal-form').modal('hide'); bsAlert(\"$msg\", \"$title\","
                            . " function () { window.location.reload() })" ]
            );
        }

        $user = $this->users->create($request->input(), false, false);
        $token = $this->tokens->create($user, Token::TYPE_REGISTRATION);

        Mail::send(
            [ 'html' => config('auth.registration.email') ],
            [
                'user'  => $user,
                'token' => $token,
            ],
            function ($message) use ($user)
            {
                $message->to($user->email, $user->name)
                        ->subject(trans('registration.email_title'));
            }
        );

        $msg = trans('registration.email_sent_text');
        $title = trans('registration.email_sent_title');
        return view(
            'layouts/script',
            [ 'script' => "$('#modal-form').modal('hide'); bsAlert(\"$msg\", \"$title\")" ]
        );
    }

    /**
     * Validate registration form field
     *
     * @param  Request $request;
     * @return Response
     */
    public function postValidateRegistrationForm(Request $request)
    {
        $rules = (new Requests\RegistrationRequest)->rules();
        $field = $request->input('field');
        $form = $request->input('form');

        $validator = Validator::make($form, $rules);
        $messages = $validator->messages()->toArray();
        if (isset($messages[$field]))
            return response()->json([ 'valid' => false, 'errors' => $messages[$field] ]);

        return response()->json([ 'valid' => true, 'errors' => [] ]);
    }
}
