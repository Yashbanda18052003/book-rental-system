<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use App\Models\Fine;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;

class StripeController extends Controller
{
    public function checkout(Rental $rental)
    {
        Stripe::setApiKey(
            env('STRIPE_SECRET')
        );

        $session = Session::create([

            'payment_method_types' => ['card'],

            'line_items' => [[

                'price_data' => [

                    'currency' => 'inr',

                    'product_data' => [
                        'name' => $rental->book->title,
                    ],

                    'unit_amount' => $rental->amount * 100,
                ],

                'quantity' => 1,

            ]],

            'mode' => 'payment',

            'success_url' =>
                route(
                    'stripe.success',
                    $rental->id
                ) .
                '?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' =>
                route('stripe.cancel', $rental->id),
        ]);

        return redirect(
            $session->url
        );
    }
    public function success(
        Request $request,
        Rental $rental
    )
    {
        if (!$rental->payment) {
    
            Payment::create([

    'rental_id' => $rental->id,

    'user_id' => Auth::id(),

    'payment_type' => 'rental',

    'stripe_payment_id' => $request->session_id,

    'amount' => $rental->amount,

    'status' => 'paid'
]);
    
            $rental->update([
                'status' => 'active'
            ]);
    
            $assignedReservation = \App\Models\Reservation::where('user_id', Auth::id())
                ->where('book_id', $rental->book_id)
                ->where('status', 'assigned')
                ->first();

            if ($assignedReservation) {
                $assignedReservation->update(['status' => 'completed']);
            }

            $rental->book->decrement('stock');

            Mail::to(Auth::user()->email)->send(new \App\Mail\RentalCreatedMail($rental));

            // Notify user
            NotificationService::send(
                Auth::user(),
                'Rental Confirmed',
                "Your rental for '{$rental->book->title}' has been confirmed. Enjoy reading!",
                'success',
                route('my.rentals')
            );

            // Notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                NotificationService::send(
                    $admin,
                    'New Rental',
                    Auth::user()->name . " rented '{$rental->book->title}'.",
                    'info',
                    route('admin.rentals')
                );
            }
        }
    
        return redirect()
            ->route('my.rentals')
            ->with(
                'success',
                'Payment Successful'
            );
    }

    public function cancel(Rental $rental)
    {
        return redirect()
            ->route('rent.form', $rental->book_id)
            ->with(
                'error',
                'Payment Cancelled'
            );
    }

    public function subscriptionCheckout(MembershipPlan $plan)
{
    $activeSubscription = Subscription::where('user_id', Auth::id())
        ->where('status', 'active')
        ->whereDate('end_date', '>=', now())
        ->exists();

    if ($activeSubscription) {

        return redirect()
            ->route('my.subscription')
            ->with(
                'error',
                'You already have an active subscription. Please wait until it expires.'
            );
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = Session::create([

        'payment_method_types' => ['card'],

        'line_items' => [[

            'price_data' => [

                'currency' => 'inr',

                'product_data' => [
                    'name' => $plan->name,
                ],

                'unit_amount' => (int) ($plan->price * 100),
            ],

            'quantity' => 1,
        ]],

        'mode' => 'payment',

        'success_url' =>
            route(
                'subscription.success',
                $plan->id
            ) . '?session_id={CHECKOUT_SESSION_ID}',

        'cancel_url' => route('membership.plans'),
    ]);

    return redirect($session->url);
}

public function subscriptionSuccess(
    Request $request,
    MembershipPlan $plan
)
{
    $startDate = Carbon::today();

    $endDate = $plan->duration == 'annual'
        ? Carbon::today()->addYear()
        : Carbon::today()->addMonth();

    $subscription = Subscription::create([
        'user_id' => Auth::id(),
        'membership_plan_id' => $plan->id,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'status' => 'active'
    ]);

    Payment::create([
        'rental_id' => null,
        'subscription_id' => $subscription->id,
        'fine_id' => null,
        'user_id' => Auth::id(),
        'payment_type' => 'subscription',
        'stripe_payment_id' => $request->session_id,
        'amount' => $plan->price,
        'status' => 'paid',
    ]);

    Mail::to(Auth::user()->email)->send(new \App\Mail\SubscriptionMail($subscription, $plan));

    // Notify user
    NotificationService::send(
        Auth::user(),
        'Subscription Activated',
        "Your '{$plan->name}' subscription is now active until " . $endDate->format('d M, Y') . ".",
        'success',
        route('my.subscription')
    );

    // Notify admins
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        NotificationService::send(
            $admin,
            'New Subscription',
            Auth::user()->name . " purchased '{$plan->name}' plan.",
            'info',
            route('admin.subscriptions')
        );
    }

    return redirect()
        ->route('my.subscription')
        ->with(
            'success',
            'Subscription Activated Successfully'
        );
}

public function fineCheckout(Fine $fine)
{

    if ($fine->fine_amount < 50) {
            $setting = \App\Models\Setting::first();
            $contact = $setting->contact_phone
                ?? $setting->contact_email
                ?? 'the admin';

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This fine (₹' . $fine->fine_amount . ') is below the ₹50 minimum for online payment. Please contact ' . $contact . ' to settle it.'
                );
        }


    Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = Session::create([

        'payment_method_types' => ['card'],

        'line_items' => [[

            'price_data' => [

                'currency' => 'inr',

                'product_data' => [
                    'name' => 'Late Return Fine',
                ],

                'unit_amount' => $fine->fine_amount * 100,
            ],

            'quantity' => 1,
        ]],

        'mode' => 'payment',

        'success_url' =>
            route(
                'fine.success',
                $fine->id
            ) . '?session_id={CHECKOUT_SESSION_ID}',

        'cancel_url' => url()->previous(),
    ]);

    return redirect($session->url);
}


public function fineSuccess(
    Request $request,
    Fine $fine
)
{
    $fine->update([
        'status' => 'paid'
    ]);

      Payment::create([

        'fine_id' => $fine->id,

        'user_id' => Auth::id(),

        'payment_type' => 'fine',

        'stripe_payment_id' => $request->session_id,

        'amount' => $fine->fine_amount,

        'status' => 'paid'
    ]);

    Mail::to($fine->user->email)
        ->send(
            new \App\Mail\FinePaidMail($fine)
        );

    return redirect()
        ->route('my.fines')
        ->with(
            'success',
            'Fine Paid Successfully'
        );
}
}