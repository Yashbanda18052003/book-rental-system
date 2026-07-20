<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:100',
            'phone' => 'required|unique:users,phone,' . auth()->id(),
        ]);

        $user = auth()->user();

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->filled('password')) {

            $request->validate([
                'password' => 'min:8|confirmed'
            ]);

            $user->password = Hash::make(
                $request->password
            );
        }

        $user->save();

        return back()->with(
            'success',
            'Profile updated successfully'
        );
    }
}