<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
   public function plans()
{
    $plans = MembershipPlan::where('status', 1)->get();

    $activeSubscription = Subscription::where('user_id', Auth::id())
        ->where('status', 'active')
        ->whereDate('end_date', '>=', now())
        ->exists();

    return view(
        'user.subscriptions.plans',
        compact('plans', 'activeSubscription')
    );
}
   public function subscribe(MembershipPlan $plan)
{

    $startDate = Carbon::today();

    $endDate = $plan->duration == 'annual'
        ? Carbon::today()->addYear()
        : Carbon::today()->addMonth();

    Subscription::create([
        'user_id' => Auth::id(),
        'membership_plan_id' => $plan->id,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'status' => 'active'
    ]);

    return redirect()
        ->route('my.subscription')
        ->with('success', 'Subscription Activated');
}
    public function mySubscription()
    {
        $subscription = Subscription::with('plan')
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        return view(
            'user.subscriptions.my-subscription',
            compact('subscription')
        );
    }
}