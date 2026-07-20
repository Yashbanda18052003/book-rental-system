<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
  public function index()
{
    $subscriptions = Subscription::with([
        'user',
        'plan'
    ])

    ->when(request('search'), function ($query) {

        $query->whereHas('user', function ($q) {

            $q->where(
                'name',
                'like',
                '%' . request('search') . '%'
            );

        })

        ->orWhereHas('plan', function ($q) {

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
        'admin.subscriptions.index',
        compact('subscriptions')
    );
}
public function cancel(Subscription $subscription)
{
    if($subscription->status != 'active')
    {
        return back()->with(
            'error',
            'Subscription already inactive.'
        );
    }

    $subscription->update([
        'status' => 'cancelled'
    ]);

    return back()->with(
        'success',
        'Subscription cancelled successfully.'
    );
}
}