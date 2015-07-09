<?php

namespace App\Http\Controllers;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $refresh = false;

        $size = $request->input('size', 15);
        if (!in_array($size, [ 15, 30, 50, 100, 0 ])) {
            $size = 15;
            $refresh = true;
        }

        $total = User::count();
        $max = ($size == 0 ? 1 : ceil($total / $size));
        $page = $request->input('page', 1);
        if ($page < 1) {
            $page = 1;
            $refresh = true;
        } else if ($page > $max) {
            $page = $max;
            $refresh = true;
        }

        $sortBy = $request->input('sort_by', 'id');
        if (!in_array($sortBy, [ 'id', 'name', 'email', 'is_active', 'is_admin' ])) {
            $sortBy = 'id';
            $refresh = true;
        }

        $sortOrder = $request->input('sort_order', 'asc');
        if (!in_array($sortOrder, [ 'asc', 'desc' ])) {
            $sortOrder = 'asc';
            $refresh = true;
        }

        if ($refresh)
            return redirect('user?page=' . $page . '&size=' . $size . '&sort_by=' . $sortBy . '&sort_order=' . $sortOrder);

        $users = User::orderBy($sortBy, $sortOrder)->paginate($size == 0 ? $total : $size);
        $users->setPath('user');
        $users->appends([ 'size' => $size, 'sort_by' => $sortBy, 'sort_order' => $sortOrder ]);

        return view('user.index', [
            'page'      => $page,
            'size'      => $size,
            'sortBy'    => $sortBy,
            'sortOrder' => $sortOrder,
            'users'     => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('user.create-form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CreateUserRequest $request
     * @return Response
     */
    public function store(Requests\CreateUserRequest $request)
    {
        $data = array_map('trim', $request->input());

        $user = new User();
        $user->name = strlen($data['name']) ? $data['name'] : null;
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->is_active = (bool)@$data['is_active'];
        $user->is_admin = (bool)@$data['is_admin'];

        try {
            $user->save();
        } catch (\Exception $e) {
            return redirect('user')->withInput()
                                   ->with('message', trans('user.create_failed'));
        }

        return view('layouts/script', [ 'script' => "$('#modal-form').modal('hide'); window.location.reload()" ]);
    }

    /**
     * Validate create user form field
     *
     * @return Response
     */
    public function validateCreateForm(Request $request)
    {
        $rules = (new Requests\CreateUserRequest)->rules();
        $field = $request->input('field');
        $form = $request->input('form');

        $validator = Validator::make($form, $rules);
        $messages = $validator->messages()->toArray();
        if (isset($messages[$field]))
            return response()->json([ 'valid' => false, 'errors' => $messages[$field] ]);

        return response()->json([ 'valid' => true, 'errors' => [] ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
