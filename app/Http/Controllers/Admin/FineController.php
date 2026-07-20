<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index()
{
    $fines = Fine::with([
        'user',
        'rental.book'
    ])

    ->when(request('search'), function ($query) {

        $query->whereHas('user', function ($q) {

            $q->where(
                'name',
                'like',
                '%' . request('search') . '%'
            );

        })

        ->orWhere(
            'status',
            'like',
            '%' . request('search') . '%'
        );

    })

    ->latest()
    ->paginate(10)
    ->withQueryString();

    return view(
        'admin.fines.index',
        compact('fines')
    );
}

    public function markPaid($id)
{
    $fine = Fine::findOrFail($id);

    $fine->update([
        'status' => 'paid'
    ]);

    return back()->with(
        'success',
        'Fine marked as paid'
    );
}

public function myFines(Request $request)
{
    $query = Fine::with([
        'rental.book'
    ])
    ->where('user_id', auth()->id());

    if($request->filled('search'))
    {
        $query->whereHas('rental.book', function($q) use ($request) {

            $q->where(
                'title',
                'like',
                '%' . $request->search . '%'
            );

        });
    }

    $fines = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view(
        'user.fines.index',
        compact('fines')
    );
}
}