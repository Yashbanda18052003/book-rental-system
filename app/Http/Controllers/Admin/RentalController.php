<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Book;
use App\Models\Setting;
use Carbon\Carbon;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;
use App\Mail\FineGeneratedMail;
class RentalController extends Controller
{
  public function index()
{
    $rentals = Rental::with([
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
        'admin.rentals.index',
        compact('rentals')
    );
}
public function returnBook($id)
{
    $rental = Rental::findOrFail($id);
    if ($rental->status === 'returned') {
        return back()->with(
            'error',
            'Book already returned'
        );
    }
    $returnDate = Carbon::today();
    $lateDays = 0;
    $fineAmount = 0;
    if ($returnDate->gt(Carbon::parse($rental->end_date))) {
        $lateDays = Carbon::parse($rental->end_date)
            ->diffInDays($returnDate);
$setting = Setting::first();
$fineAmount = $lateDays * $setting->fine_per_day;
        $fine = \App\Models\Fine::create([
    'rental_id' => $rental->id,
    'user_id' => $rental->user_id,
    'late_days' => $lateDays,
    'fine_amount' => $fineAmount,
    'status' => 'pending'
]);
Mail::to($rental->user->email)
    ->send(
        new FineGeneratedMail($fine)
    );
        NotificationService::send(
            $rental->user,
            "Fine Pending",
            "A fine of ₹{$fineAmount} has been added for late return of '{$rental->book->title}'. Please pay it.",
            'danger',
            route('my.fines')
        );
     
    }
    $rental->update([
    'returned_at' => $returnDate,
    'status' => 'returned',
    'fine' => $fineAmount
]);
    Mail::to($rental->user->email)
        ->send(
            new \App\Mail\RentalReturnedMail($rental)
        );
    NotificationService::send(
        $rental->user,
        "Book Returned",
        "Your rental for '{$rental->book->title}' has been successfully closed.",
        'info',
        route('my.rentals')
    );
$book = Book::findOrFail($rental->book_id);
\Log::info('RETURN BOOK STARTED', [
    'rental_id' => $rental->id,
    'book_id' => $rental->book_id
]);
\Log::info('BOOK FOUND', [
    'book_id' => $book->id,
    'stock_before' => $book->stock
]);
$book->increment('stock');
$book->refresh();
\Log::info('BOOK UPDATED', [
    'book_id' => $book->id,
    'stock_after_return' => $book->stock
]);
$nextReservation = \App\Models\Reservation::where(
    'book_id',
    $rental->book_id
)
->where(
    'status',
    'waiting'
)
->orderBy(
    'queue_position'
)
->first();
if ($nextReservation) {
    $nextReservation->update([
        'status' => 'available',
        'available_at' => now(),
    ]);

    \Illuminate\Support\Facades\Mail::to($nextReservation->user->email)
        ->send(new \App\Mail\ReservationAvailableMail($nextReservation));

        NotificationService::send(
            $nextReservation->user,
            'Book Available',
            "'{$nextReservation->book->title}' is now available. Please contact admin to assign it to you within 48 hours.",
            'info',
            route('my.reservations')
        );
}
return back()->with(
    'success',
    'Book returned successfully'
);
}
}