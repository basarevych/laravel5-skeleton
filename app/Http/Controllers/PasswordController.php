<?php

namespace App\Http\Controllers;

use Validator;
use Mail;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;

use App\Contracts\Repositories\PasswordResets;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    /**
     * Password resets repository
     *
     * @var PasswordResets
     */
    protected $passwordResets;

    /**
     * Constructor
     *
     * @param PasswordResets $passwordResets
     */
    public function __construct(PasswordResets $passwordResets)
    {
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
        $user = \App\User::where('email', $request->input('email'))
                           ->firstOrFail();

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
