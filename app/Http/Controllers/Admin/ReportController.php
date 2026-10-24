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
        $format = request('type', 'excel');

        if ($format === 'pdf') {
            $rentals = Rental::with(['user', 'book'])->latest()->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.rentals_pdf', compact('rentals'));
            return $pdf->download('RentalReports.pdf');
        }

        return Excel::download(
            new RentalsExport,
            'RentalReports.xlsx'
        );
    }

    public function exportSubscriptions()
    {
        $format = request('type', 'excel');

        if ($format === 'pdf') {
            $subscriptions = Subscription::with(['user', 'plan'])->latest()->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.subscriptions_pdf', compact('subscriptions'));
            return $pdf->download('SubscriptionReports.pdf');
        }

        return Excel::download(
            new SubscriptionsExport,
            'SubscriptionReports.xlsx'
        );
    }

    public function exportFines()
    {
        $format = request('type', 'excel');

        if ($format === 'pdf') {
            $fines = Fine::with(['user', 'rental.book'])->latest()->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.fines_pdf', compact('fines'));
            return $pdf->download('FineReports.pdf');
        }

        return Excel::download(
            new FinesExport,
            'FineReports.xlsx'
        );
    }
}