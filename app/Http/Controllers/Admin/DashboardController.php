<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Fine;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
{
    $totalUsers = User::count();

    $totalBooks = Book::count();

    $activeRentals = Rental::where(
        'status',
        'active'
    )->count();

    $overdueRentals = Rental::where(
        'status',
        'overdue'
    )->count();

    $revenue = Payment::where(
        'status',
        'paid'
    )->sum('amount');

    $activeSubscriptions = Subscription::where(
        'status',
        'active'
    )->count();

    $pendingFines = Fine::where(
        'status',
        'pending'
    )->count();

    $paidFines = Fine::where(
        'status',
        'paid'
    )->sum('fine_amount');

    $lowStockBooks = Book::where('stock', '<=', 2)
        ->orderBy('stock')
        ->take(5)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Top Borrowed Books
    |--------------------------------------------------------------------------
    */

    $topBorrowedBooks = Book::select(
            'books.id',
            'books.title',
            DB::raw('COUNT(rentals.id) as total_borrows')
        )
        ->join(
            'rentals',
            'books.id',
            '=',
            'rentals.book_id'
        )
        ->groupBy(
            'books.id',
            'books.title'
        )
        ->orderByDesc('total_borrows')
        ->take(5)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Pending Reservations
    |--------------------------------------------------------------------------
    */

    $pendingReservations = Reservation::select(
            'book_id',
            DB::raw('COUNT(*) as waiting_count')
        )
        ->with('book')
        ->where('status', 'waiting')
        ->groupBy('book_id')
        ->orderByDesc('waiting_count')
        ->take(5)
        ->get();

        $monthlyRevenue = [];

$monthlyRentals = [];

for ($i = 1; $i <= 12; $i++) {

    $monthlyRevenue[] = Payment::where('status', 'paid')
        ->whereMonth('created_at', $i)
        ->sum('amount');

    $monthlyRentals[] = Rental::whereMonth('created_at', $i)
        ->count();
}

$topBooks = Rental::select(
        'books.title',
        DB::raw('COUNT(rentals.id) as total_rentals')
    )
    ->join('books', 'rentals.book_id', '=', 'books.id')
    ->groupBy('books.id', 'books.title')
    ->orderByDesc('total_rentals')
    ->take(5)
    ->get();

    return view(
        'admin.dashboard',
        compact(
            'totalUsers',
            'totalBooks',
            'activeRentals',
            'overdueRentals',
            'revenue',
            'activeSubscriptions',
            'pendingFines',
            'paidFines',
            'lowStockBooks',
            'topBorrowedBooks',
            'pendingReservations',
            'monthlyRevenue',
            'monthlyRentals',
            'topBooks'
        )
    );
}
}