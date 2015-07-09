<?php

namespace App\Http\Controllers;

use Auth;
use Validator;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Display profile form
     *
     * @return Response
     */
    public function getProfileForm()
    {
        return view('profile.profile-form', [ 'user' => Auth::user() ]);
    }

    /**
     * Process profile form
     *
     * @param \App\Http\Requests\ProfileRequest $request
     * @return Response
     */
    public function postProfileForm(Requests\ProfileRequest $request)
    {
        $data = array_map('trim', $request->input());

        $user = Auth::user();
        $user->name = strlen($data['name']) ? $data['name'] : null;
        $user->email = $data['email'];
        if (strlen($data['password']))
            $user->password = bcrypt($data['password']);

        try {
            $user->save();
        } catch (\Exception $e) {
            return redirect('profile-form')->withInput()
                                           ->with('message', trans('profile.save_failed'));
        }

        return view('layouts/script', [ 'script' => "$('#modal-form').modal('hide'); window.location.reload()" ]);
    }

    /**
     * Validate profile form field
     *
     * @return Response
     */
    public function postValidateProfileForm(Request $request)
    {
        $rules = (new Requests\ProfileRequest)->rules();
        $field = $request->input('field');
        $form = $request->input('form');

        $validator = Validator::make($form, $rules);
        $messages = $validator->messages()->toArray();
        if (isset($messages[$field]))
            return response()->json([ 'valid' => false, 'errors' => $messages[$field] ]);

        return response()->json([ 'valid' => true, 'errors' => [] ]);
    }
}
