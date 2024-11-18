<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Admin/User/Index', [
            'users' => User::with('roles')->paginate(12),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Admin/User/Create', [
            'roles' => Role::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['string', 'required', 'min:4', 'max:255', 'unique:users'],
            'email' => ['string', 'required', 'email', 'max:255', 'unique:users'],
            'name' => ['string', 'required', 'min:4', 'max:255'],
            'password' => ['string', 'required', 'min:8', 'max:255'],
            'roles' => ['array', 'exists:roles,id'],
        ]);

        $user = new User([
            'email' => $request->email,
            'username' => $request->username,
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);
        $user->save();

        $user->syncRoles($request->roles);

        return redirect()->back()->with('message', 'User created.')->with('user', $user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return inertia('Admin/User/Edit', [
            'user' => $user->load('roles'),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['string', 'required', 'min:4', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'roles' => ['array', 'exists:roles,id'],
        ]);

        $user->name = $request->name;
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        $user->syncRoles($request->roles);

        return redirect()->back()->with('message', 'User updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('message', 'User deleted.');
    }
}
