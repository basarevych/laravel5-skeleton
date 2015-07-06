<?php

namespace App\Http\Controllers;

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
        $page = $request->input('page', 1);

        $sortBy = $request->input('sort_by', 'id');
        if (!in_array($sortBy, [ 'id', 'name', 'email', 'is_active', 'is_admin' ]))
            $sortBy = 'id';

        $sortOrder = $request->input('sort_order', 'asc');
        if (!in_array($sortOrder, [ 'asc', 'desc' ]))
            $sortOrder = 'asc';

        $users = User::orderBy($sortBy, $sortOrder)->paginate(15);
        $users->setPath('user');

        return view('user.index', [
            'page'      => $page,
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
