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
        $refresh = false;

        $size = $request->input('size', 15);
        if (!in_array($size, [ 15, 30, 50, 0 ])) {
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
