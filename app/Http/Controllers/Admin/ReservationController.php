<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationAssignedMail;
use App\Models\Reservation;
use App\Models\Book;
use App\Models\Rental;
use App\Services\NotificationService;

class ReservationController extends Controller
{
   public function index()
{
    $reservations = Reservation::with([
        'user',
        'book'
    ])

    ->when(request('search'), function ($query) {

        $query->whereHas('user', function ($q) {
            $q->where(
                'name',
                'like',
                '%' . request('search') . '%'
            );
        })

        ->orWhereHas('book', function ($q) {
            $q->where(
                'title',
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
        'admin.reservations.index',
        compact('reservations')
    );
}

    public function assign($id)
{
    $reservation = Reservation::with(['book', 'user'])
        ->findOrFail($id);

    if ($reservation->book->stock < 1) {

    return back()->with(
        'error',
        'Book stock not available'
    );
}
    // Rental will not be automatically created anymore. User will manually rent the book.

    $reservation->update([
        'status' => 'assigned'
    ]);

    Mail::to($reservation->user->email)
    ->send(
        new ReservationAssignedMail($reservation)
    );

    // Stock is now decremented only when the rental payment succeeds (see StripeController::success),
    // not here — assigning a reservation should not remove a copy from stock until it's actually rented.

    // Notify the user that the book is available for them to pay and rent
    NotificationService::send(
        $reservation->user,
        'Book Available - Pay Now',
        "'{$reservation->book->title}' is now available for you! Go to My Reservations and click Pay Now.",
        'success',
        route('my.reservations')
    );

    return back()->with(
        'success',
        'Reservation assigned successfully'
    );
}
}