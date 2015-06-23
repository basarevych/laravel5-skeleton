<?php

namespace App\Http\Controllers;

use Validator;
use Mail;

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
        if (!$user)
            abort(404, "User not found");

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

        return view('layouts/script', [ 'script' => "$('#modal-form').modal('hide');" ]);
    }

    /**
     * Validate login form field
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
}
