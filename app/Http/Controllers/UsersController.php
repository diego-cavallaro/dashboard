<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doc;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::Allowed()->get();
        return view('users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('update', $user);
        $permissions = Permission::pluck('name', 'id');
        $roles = Role::pluck('description', 'id');
        return view('users.show', compact('user', 'roles', 'permissions'));
    }

    /**
     * Edit the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function enable(User $user)
    {
        return $request;
    }

    /**
     * Disable the specified resource from storage.
     */
    public function disable(User $user)
    {
        //
    }
}
