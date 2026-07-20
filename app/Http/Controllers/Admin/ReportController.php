<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Subscription;
use App\Models\Fine;
use App\Exports\RentalsExport;
use App\Exports\SubscriptionsExport;
use App\Exports\FinesExport;
use Maatwebsite\Excel\Facades\Excel;



class ReportController extends Controller
{
    public function index()
    {
        $rentals = Rental::with([
            'user',
            'book'
        ])->latest()->paginate(10);

        $subscriptions = Subscription::with([
            'user',
            'plan'
        ])->latest()->paginate(10);

        $fines = Fine::with([
            'user',
            'rental.book'
        ])->latest()->paginate(10);

        return view(
            'admin.reports.index',
            compact(
                'rentals',
                'subscriptions',
                'fines'
            )
        );
    }

public function exportRentals()
{
    return Excel::download(
        new RentalsExport,
        'RentalReports.xlsx'
    );
}

public function exportSubscriptions()
{
    return Excel::download(
        new SubscriptionsExport,
        'SubscriptionReports.xlsx'
    );
}

public function exportFines()
{
    return Excel::download(
        new FinesExport,
        'FineReports.xlsx'
    );
}
}