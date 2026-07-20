<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    public function index()
{
    $payments = Payment::with([
        'user',
        'rental.book',
        'subscription.plan',
        'fine'
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
            'payment_type',
            'like',
            '%' . request('search') . '%'
        )

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
        'admin.payments.index',
        compact('payments')
    );
}

public function export()
{
    return Excel::download(
        new PaymentsExport,
        'PaymentsReport.xlsx'
    );
}
}