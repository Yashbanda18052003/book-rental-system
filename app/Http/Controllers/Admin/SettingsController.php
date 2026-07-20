<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        if (!$setting) {

            $setting = Setting::create([
                'fine_per_day' => 10,
                'rental_days' => 7,
                'contact_email' => null,
                'contact_phone' => null,
            ]);
        }

        return view(
            'admin.settings.index',
            compact('setting')
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'fine_per_day' => 'required|integer|min:1',
            'rental_days' => 'required|integer|min:1',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable'
        ]);

        $setting = Setting::first();

        if (!$setting) {

            $setting = Setting::create([
                'fine_per_day' => 10,
                'rental_days' => 7,
                'contact_email' => null,
                'contact_phone' => null,
            ]);
        }

        $setting->update([
            'fine_per_day' => $request->fine_per_day,
            'rental_days' => $request->rental_days,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
        ]);

        return back()->with(
            'success',
            'Settings updated successfully'
        );
    }
}