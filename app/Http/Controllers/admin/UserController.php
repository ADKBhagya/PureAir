<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Show all users except web_master
        $users = User::where('role', '!=', 'web_master')->get();
        return view('pages.admin.user-management', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:Admin,Super Admin',
            'status' => 'required|in:Active,Inactive',
        ]);

        User::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => strtolower(str_replace(' ', '_', $validated['role'])), // admin | super_admin
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.user.management')->with('success', 'Admin added successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => 'required|email|unique:users,email,' . $user->id,
            'editPassword' => 'nullable|min:6',
            'editRole' => 'required|in:Admin,Super Admin',
            'editStatus' => 'required|in:Active,Inactive',
        ]);

        $user->full_name = $validated['editName'];
        $user->email = $validated['editEmail'];
        $user->role = $validated['editRole'] === 'Super Admin' ? 'super_admin' : 'admin';
        $user->status = $validated['editStatus'];

        if (!empty($validated['editPassword'])) {
            $user->password = Hash::make($validated['editPassword']);
        }

        $user->save();

        return redirect()->route('admin.user.management')->with('success', 'Admin updated successfully!');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('admin.user.management')->with('success', 'Admin deleted successfully!');
    }
}
