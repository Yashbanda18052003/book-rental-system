<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = auth()->user();

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('uploads/profiles');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Delete old picture if exists
            if ($user->profile_picture) {
                if (file_exists(public_path($user->profile_picture))) {
                    @unlink(public_path($user->profile_picture));
                }
                if (Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
            }

            $file->move($destinationPath, $filename);
            $user->profile_picture = 'uploads/profiles/' . $filename;
        }

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|max:12|confirmed'
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