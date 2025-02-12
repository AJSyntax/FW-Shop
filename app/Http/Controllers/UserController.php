<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function toggleBlock(User $user)
    {
        $user->update(['is_blocked' => !$user->is_blocked]);
        $status = $user->is_blocked ? 'blocked' : 'unblocked';
        return redirect()->back()->with('success', "User has been {$status}.");
    }
} 