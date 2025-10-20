<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function admins()
    {
        $admins = User::get();
        return view('admin.admins', compact('admins'));
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'নতুন অ্যাডমিন তৈরি হয়েছে');
    }

    public function updateAdmin(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'অ্যাডমিন তথ্য আপডেট হয়েছে');
    }

    public function deleteAdmin(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'আপনি নিজেকে ডিলিট করতে পারবেন না');
        }

        // Prevent deleting the last admin
        if (User::count() <= 1) {
            return back()->with('error', 'সর্বশেষ অ্যাডমিন ডিলিট করা যাবে না');
        }

        $user->delete();

        return back()->with('success', 'অ্যাডমিন ডিলিট হয়েছে');
    }
}
