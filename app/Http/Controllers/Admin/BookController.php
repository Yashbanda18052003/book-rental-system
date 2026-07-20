<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Book;
use App\Models\Rental;
use App\Models\Reservation;
use App\Models\Fine;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
class BookController extends Controller
{
   public function index()
{
    $books = Book::query()
        ->when(request('search'), function ($query) {
            $query->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('author', 'like', '%' . request('search') . '%')
                  ->orWhere('isbn', 'like', '%' . request('search') . '%');
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();
    return view(
        'admin.books.index',
        compact('books')
    );
}
    public function create()
    {
        return view('admin.books.create');
    }
    public function store(Request $request)
    {
       $request->validate([
    'title' => 'required|max:255',
    'author' => 'required|max:255',
    'isbn' => 'required|unique:books,isbn',
    'description' => 'nullable|min:10|max:200',
    'rental_price' => 'required|numeric|min:10|max:500',
    'stock' => 'required|integer|min:0|max:100',
    'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
], [
    'title.required' => 'Book title is required.',
    'author.required' => 'Author name is required.',
    'isbn.required' => 'ISBN is required.',
    'isbn.unique' => 'This ISBN already exists.',
        'description.min' => 'Description must be at least 10 characters.',
    'description.max' => 'Description cannot exceed 200 characters.',
    'rental_price.required' => 'Rental price is required.',
    'rental_price.numeric' => 'Rental price must be a number.',
    'rental_price.min' => 'Rental price must be at least ₹10.',
'rental_price.max' => 'Rental price cannot exceed ₹500.',
'stock.min' => 'Stock cannot be negative.',
'stock.max' => 'Stock cannot exceed 100 copies.',
    'stock.required' => 'Stock is required.',
    'stock.integer' => 'Stock must be a whole number.',
    'stock.min' => 'Stock cannot be negative.',
'stock.max' => 'Stock cannot exceed 100 copies.',
    'image.required' => 'Book image is required.',
    'image.image' => 'Please upload a valid image.',
    'image.mimes' => 'Only JPG, JPEG and PNG images are allowed.',
    'image.max' => 'Image size must be less than 5 MB.',
]);
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'_'.$request->image->getClientOriginalName();
            $request->image->move(
                public_path('uploads/books'),
                $imageName
            );
        }
        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'rental_price' => $request->rental_price,
            'stock' => $request->stock,
            'image' => $imageName,
            'status' => 1
        ]);
        return redirect()
            ->route('books.index')
            ->with('success', 'Book Added Successfully');
    }
   public function edit(Book $book)
{
    return view('admin.books.edit', compact('book'));
}
public function update(Request $request, Book $book)
{
$request->validate([
'title' => 'required|max:255',
'author' => 'required|max:255',
'isbn' => 'required|unique:books,isbn,' . $book->id,
'description' => 'nullable|min:10|max:200',
'rental_price' => 'required|numeric|min:10|max:500',
'stock' => 'required|integer|min:0|max:100',
'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
],[
'title.required' => 'Book title is required.',
'author.required' => 'Author name is required.',
    'isbn.required' => 'ISBN is required.',
    'isbn.unique' => 'This ISBN already exists.',
        'description.min' => 'Description must be at least 10 characters.',
    'description.max' => 'Description cannot exceed 200 characters.',
    'rental_price.required' => 'Rental price is required.',
    'rental_price.numeric' => 'Rental price must be a number.',
        'rental_price.min' => 'Rental price must be at least ₹10.',
'rental_price.max' => 'Rental price cannot exceed ₹500.',
    'stock.required' => 'Stock is required.',
    'stock.integer' => 'Stock must be a whole number.',
    'stock.min' => 'Stock cannot be negative.',
'stock.max' => 'Stock cannot exceed 100 copies.',
    'image.image' => 'Please upload a valid image.',
    'image.mimes' => 'Only JPG, JPEG and PNG images are allowed.',
    'image.max' => 'Image size must be less than 5 MB.',
]);
if ($request->hasFile('image')) {
    if (
        $book->image &&
        file_exists(public_path('uploads/books/' . $book->image))
    ) {
        unlink(public_path('uploads/books/' . $book->image));
    }
    $imageName = time() . '_' .
        $request->image->getClientOriginalName();
    $request->image->move(
        public_path('uploads/books'),
        $imageName
    );
    $book->image = $imageName;
}
$book->title = $request->title;
$book->author = $request->author;
$book->isbn = $request->isbn;
$book->description = $request->description;
$book->rental_price = $request->rental_price;
$book->stock = $request->stock;
$book->save();
return redirect()
    ->route('books.index')
    ->with('success', 'Book Updated Successfully');
}
public function destroy(Book $book)
{
    if ($book->image && file_exists(public_path('uploads/books/' . $book->image))) {
        unlink(public_path('uploads/books/' . $book->image));
    }
    $book->delete();
    return response()->json([
        'success' => true
    ]);
}
public function catalog(Request $request)
{
    $query = Book::query();
    if($request->filled('search'))
    {
        $query->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('author', 'like', '%' . $request->search . '%');
    }
    $books = $query
        ->latest()
        ->paginate(8)
        ->withQueryString();
    return view('user.books.catalog', compact('books'));
}
public function rentForm(Book $book)
{
    $pendingFine = \App\Models\Fine::where(
        'user_id',
        auth()->id()
    )
    ->where('status', 'pending')
    ->exists();
    if ($pendingFine) {
        return redirect()
            ->back()
            ->with(
                'error',
                'Please clear pending fines first'
            );
    }
    $hasAssignedReservation = \App\Models\Reservation::where('user_id', auth()->id())
        ->where('book_id', $book->id)
        ->where('status', 'assigned')
        ->exists();

    if ($book->stock <= 0 && !$hasAssignedReservation) {
        return redirect()
            ->back()
            ->with(
                'error',
                'Book is out of stock'
            );
    }
    return view(
        'user.books.rent',
        compact('book')
    );
}
public function rentStore(Request $request, Book $book)
{
$request->validate([
'start_date' => [
'required',
'date',
'after_or_equal:today',
'before_or_equal:' . date('Y-12-31'),
],
    'end_date' => [
        'required',
        'date',
        'after:start_date',
        'before_or_equal:' . date('Y-12-31'),
    ],
], [
    'start_date.required' => 'Start date is required.',
    'start_date.after_or_equal' => 'Start date cannot be in the past.',
    'start_date.before_or_equal' => 'Start date must be within the current year.',
    'end_date.required' => 'End date is required.',
    'end_date.after' => 'End date must be after start date.',
    'end_date.before_or_equal' => 'End date must be within the current year.',
]);
$alreadyRented = Rental::where(
    'user_id',
    auth()->id()
)
->where('book_id', $book->id)
->whereIn('status', [
    'pending',
    'active'
])
->exists();
if ($alreadyRented) {
    return back()->with(
        'error',
        'You already rented this book.'
    );
}
$days = \Carbon\Carbon::parse($request->start_date)
    ->diffInDays(
        \Carbon\Carbon::parse($request->end_date)
    );
if ($days > 15) {
    return redirect()
        ->back()
        ->withErrors([
            'end_date' => 'You can rent a book for a maximum of 15 days only.'
        ])
        ->withInput();
}
$amount = $days * $book->rental_price;
if ($amount < 50) {
    return redirect()
        ->back()
        ->withErrors([
            'end_date' => 'Minimum rental amount must be ₹50.'
        ])
        ->withInput();
}
$freeRentalCount = Rental::where(
    'user_id',
    auth()->id()
)
->whereIn('status', ['active', 'returned'])
->count();
if ($freeRentalCount >= 5) {
    $subscription = \App\Models\Subscription::with('plan')
        ->where('user_id', auth()->id())
        ->where('status', 'active')
        ->first();
    if (!$subscription) {
        return back()->with(
            'error',
            'You have used all 5 free rentals. Please purchase a membership plan.'
        );
    }
    /*
    |--------------------------------------------------------------------------
    | Count ONLY rentals after subscription started
    |--------------------------------------------------------------------------
    */
    $planRentals = Rental::where(
        'user_id',
        auth()->id()
    )
    ->whereDate(
        'created_at',
        '>=',
        $subscription->start_date
    )
    ->whereIn('status', ['active', 'returned'])
    ->count();
    if (
        $planRentals >=
        $subscription->plan->rental_limit
    ) {
        return back()->with(
            'error',
            'Rental limit reached for your membership plan.'
        );
    }
}
$rental = Rental::create([
    'user_id' => Auth::id(),
    'book_id' => $book->id,
    'start_date' => $request->start_date,
    'end_date' => $request->end_date,
    'amount' => $amount,
    'status' => 'pending'
]);
return redirect()->route(
    'stripe.checkout',
    $rental->id
);
}
public function myRentals(Request $request)
{
    $query = Rental::with('book')
        ->where('user_id', auth()->id());
    if($request->filled('search'))
    {
        $query->whereHas('book', function($q) use ($request) {
            $q->where(
                'title',
                'like',
                '%' . $request->search . '%'
            );
        });
    }
    $rentals = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();
    return view(
        'user.books.my-rentals',
        compact('rentals')
    );
}
public function reserveBook(Book $book)
{
    $alreadyReserved = Reservation::where(
        'user_id',
        auth()->id()
    )
    ->where('book_id', $book->id)
    ->where('status', 'waiting')
    ->exists();
    if ($alreadyReserved) {
        return back()->with(
            'error',
            'You already reserved this book.'
        );
    }
    $queuePosition = Reservation::where(
        'book_id',
        $book->id
    )->count() + 1;
   $reservation = Reservation::create([
        'user_id' => auth()->id(),
        'book_id' => $book->id,
        'queue_position' => $queuePosition,
        'status' => 'waiting',
    ]);

    \Illuminate\Support\Facades\Mail::to(auth()->user()->email)
        ->send(new \App\Mail\ReservationCreatedMail($reservation));

    // Notify the user

    // Notify the user
    NotificationService::send(
        auth()->user(),
        'Book Reserved',
        "You have successfully reserved '{$book->title}'. You will be notified when it becomes available.",
        'info',
        route('my.reservations')
    );

    // Notify all admins
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        NotificationService::send(
            $admin,
            'New Reservation',
            auth()->user()->name . " has reserved '{$book->title}'.",
            'info',
            route('admin.reservations')
        );
    }

    return back()->with(
        'success',
        'Book reserved successfully.'
    );
}
public function myReservations(Request $request)
{
    $query = Reservation::with('book')
        ->where('user_id', auth()->id());
    if ($request->filled('search'))
    {
        $query->whereHas('book', function ($q) use ($request) {
            $q->where(
                'title',
                'like',
                '%' . $request->search . '%'
            );
        });
    }
    $reservations = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();
    return view(
        'user.books.my-reservations',
        compact('reservations')
    );
}
}