<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::latest()->get();

        return view(
            'admin.memberships.index',
            compact('plans')
        );
    }

    public function create()
    {
        return view('admin.memberships.create');
    }

    public function store(Request $request)
    {
        $request->validate([
    'name' => 'required|max:20',
    'price' => 'required|numeric|min:0|max:9999',
    'duration' => 'required|in:monthly,annual',
    'rental_limit' => 'required|integer|min:1|max:20',
    'description' => 'nullable|min:10|max:100',
],[
    'name.required' => 'Plan name is required.',

    'price.required' => 'Price is required.',
    'price.numeric' => 'Price must be numeric.',

    'duration.required' => 'Please select duration.',

    'rental_limit.required' => 'Rental limit is required.',
    'rental_limit.max' => 'Rental limit cannot exceed 20.',

        'description.min' => 'Description must contain at least 10 characters.',
    'description.max' => 'Description cannot exceed 100 characters.',
]);

        MembershipPlan::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'rental_limit' => $request->rental_limit,
            'description' => $request->description,
            'status' => 1
        ]);

        return redirect()
            ->route('plans.index')
            ->with('success', 'Plan Added Successfully');
    }
}