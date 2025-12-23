<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the user.
     */
    public function index()
    {
        $users = User::all()->load('role');
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'avatar' => 'upload/avt/default.png',
                'username' => $request->username,
                'password' => Hash::make('default_password'),
                'gender' => $request->gender,
                'role_id' => $request->role_id,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User has been created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while creating the user. Please try again! error: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Show the form for editing the specified user.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        DB::beginTransaction();

        try {
            $users = User::findOrFail($id);

            $avatarPath = $users->avatar;
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('upload/avt'), $imageName);
                $avatarPath = 'upload/avt/' . $imageName;
            }

            $users->update([
                'email' => $request->email,
                'name' => $request->name,
                'gender' => $request->gender,
                'role_id' => $request->role_id,
                'avatar' => $avatarPath,
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User information has been updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while updating user information. Please try again! error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            if (Auth::user()->id == $id) {
                DB::rollBack();
                return redirect()->back()->with('error', 'You cannot change status your own account!');
            }

            $users = User::findOrFail($id);
            $users->status = !$users->status;
            $users->save();

            DB::commit();

            return redirect()->back()->with('success', 'User status has been updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while updating user status. Please try again! error: ' . $e->getMessage());
        }
    }
}
