<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\MembershipPlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ActivityLogController;


/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Route::get('/verify-phone', [AuthController::class, 'showVerifyPhone'])->name('verify.phone');
    // Route::post('/verify-phone', [AuthController::class, 'verifyPhone'])->name('verify.phone.post');

  
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (USER + ADMIN SAFE)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |-------------------------
    | ROLE-BASED DASHBOARD FIX
    |-------------------------
    */

    Route::get('/dashboard', function () {

        if (!auth()->check()) {
            return redirect('/login');
        }

        // 🔥 IMPORTANT FIX: stop admin going to user dashboard
        return auth()->user()->role === 'admin'
            ? redirect('/admin/dashboard')
            : view('user.dashboard');

    })->name('dashboard');

    Route::get('/books', [BookController::class, 'catalog'])->name('catalog');

    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/rent-book/{book}', [BookController::class, 'rentForm'])->name('rent.form');
    Route::post('/rent-book/{book}', [BookController::class, 'rentStore'])->name('rent.store');

    Route::get('/my-rentals', [BookController::class, 'myRentals'])->name('my.rentals');

    Route::post('/reserve-book/{book}', [BookController::class, 'reserveBook'])->name('reserve.book');

    Route::get('/my-reservations', [BookController::class, 'myReservations'])->name('my.reservations');

    Route::get('/membership-plans', [SubscriptionController::class, 'plans'])->name('membership.plans');

    // Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe.plan');

    Route::get('/my-subscription', [SubscriptionController::class, 'mySubscription'])->name('my.subscription');

    Route::get('/stripe/checkout/{rental}', [StripeController::class, 'checkout'])->name('stripe.checkout');

    Route::get('/stripe/success/{rental}', [StripeController::class, 'success'])->name('stripe.success');

    Route::get('/stripe/cancel/{rental}', [StripeController::class, 'cancel'])->name('stripe.cancel');

    Route::get('/stripe/subscription/{plan}',
    [StripeController::class, 'subscriptionCheckout'])
    ->name('subscription.checkout');

Route::get('/stripe/subscription/success/{plan}',
    [StripeController::class, 'subscriptionSuccess'])
    ->name('subscription.success');


Route::get('/stripe/fine/{fine}',
    [StripeController::class, 'fineCheckout'])
    ->name('fine.checkout');

Route::get('/stripe/fine/success/{fine}',
    [StripeController::class, 'fineSuccess'])
    ->name('fine.success');

    Route::get('/my-fines',
    [FineController::class,'myFines'])
    ->name('my.fines');

      Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'index'])
    ->name('profile');

Route::post('/profile/update', [App\Http\Controllers\User\ProfileController::class, 'update'])
    ->name('profile.update');

    // Notification Routes
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark.all.read');
    Route::post('/notifications/{notification}/mark-read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.mark.read');
    Route::get('/notifications/count', [App\Http\Controllers\NotificationController::class, 'count'])->name('notifications.count');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (FULL PROTECTION)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/admin/books/data', [BookController::class, 'getBooks'])->name('books.data');

    Route::get('/admin/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/admin/books/store', [BookController::class, 'store'])->name('books.store');

    Route::get('/admin/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/admin/books/{book}', [BookController::class, 'update'])->name('books.update');
 Route::delete(
    '/admin/books/{book}',
    [BookController::class, 'destroy']
)->name('books.destroy');

    Route::get('/admin/rentals', [RentalController::class, 'index'])->name('admin.rentals');
    Route::post('/admin/rentals/{rental}/return', [RentalController::class, 'returnBook'])->name('rentals.return');

    Route::get('/admin/reservations', [ReservationController::class, 'index'])->name('admin.reservations');
    Route::post('/admin/reservations/{reservation}/assign', [ReservationController::class, 'assign'])->name('reservations.assign');

    Route::get('/admin/membership-plans', [MembershipPlanController::class, 'index'])->name('plans.index');
    Route::get('/admin/membership-plans/create', [MembershipPlanController::class, 'create'])->name('plans.create');
    Route::post('/admin/membership-plans/store', [MembershipPlanController::class, 'store'])->name('plans.store');

 Route::get(
    '/admin/subscriptions',
    [AdminSubscriptionController::class, 'index']
)->name('admin.subscriptions');

    Route::post(
    '/admin/subscriptions/{subscription}/cancel',
    [AdminSubscriptionController::class, 'cancel']
)->name('admin.subscription.cancel');

    Route::get('/admin/fines', [FineController::class, 'index'])->name('admin.fines');
    Route::post('/admin/fines/{fine}/pay', [FineController::class, 'markPaid'])->name('fines.pay');

    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('admin.payments');

Route::get(
    '/admin/reports',
    [ReportController::class, 'index']
)->name('reports');


Route::get('/reports/export/rentals',
    [ReportController::class, 'exportRentals'])
    ->name('reports.export.rentals');

Route::get('/reports/export/subscriptions',
    [ReportController::class, 'exportSubscriptions'])
    ->name('reports.export.subscriptions');

Route::get('/reports/export/fines',
    [ReportController::class, 'exportFines'])
    ->name('reports.export.fines');

    Route::get(
    '/admin/payments/export',
    [PaymentController::class, 'export']
)->name('admin.payments.export');

Route::get(
    '/admin/activity-logs',
    [ActivityLogController::class,'index']
)->name('admin.activity.logs');

Route::get(
    '/admin/profile',
    [App\Http\Controllers\Admin\ProfileController::class, 'index']
)->name('admin.profile');

Route::post(
    '/admin/profile/update',
    [App\Http\Controllers\Admin\ProfileController::class, 'update']
)->name('admin.profile.update');

Route::get(
    '/admin/settings',
    [SettingsController::class, 'index']
)->name('admin.settings');

Route::post(
    '/admin/settings',
    [SettingsController::class, 'update']
)->name('admin.settings.update');

});



/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

// Route::get('/test-mail', function () {

//     Mail::raw('Email working successfully!', function ($message) {
//         $message->to('yash.glsbca21@gmail.com')
//                 ->subject('Laravel Mail Test');
//     });

//     return 'Mail Sent';
// });


Route::get('/forgot-password',
    [ForgotPasswordController::class,'showLinkRequestForm'])
    ->name('password.request');

Route::post('/forgot-password',
    [ForgotPasswordController::class,'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}',
    [ForgotPasswordController::class,'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password',
    [ForgotPasswordController::class,'resetPassword'])
    ->name('password.update');

Route::get('/', function () {
    return redirect('/login');
});