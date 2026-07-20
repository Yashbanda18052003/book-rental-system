@extends('user.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 fade-in-el">
    <h2>My Subscription</h2>
</div>

<div class="row fade-in-el">
    <div class="col-md-6">
        @if($subscription)
            <div class="card p-4 text-center" style="border-top: 4px solid var(--accent-emerald) !important;">
                <div class="card-body">
                    <div class="metric-label mb-2">Active Plan</div>
                    <h2 class="classic-serif brand-accent-text mb-4">{{ $subscription->plan->name }}</h2>
                    
                    <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-10">
                        <span class="text-secondary">Status</span>
                        <span class="badge bg-success">{{ ucfirst($subscription->status) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-10">
                        <span class="text-secondary">Start Date</span>
                        <span class="fw-medium text-light">{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M, Y') }}</span>
                    </div>

                    <div class="d-flex justify-content-between py-2 mb-4">
                        <span class="text-secondary">Renewal Date</span>
                        <span class="fw-medium text-light">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M, Y') }}</span>
                    </div>

                    @if(\Carbon\Carbon::parse($subscription->end_date)->isPast())
    <a href="{{ route('membership.plans') }}"
       class="btn btn-warning w-100">
        Renew Subscription
    </a>
@else
    <button class="btn btn-secondary w-100" disabled>
        Subscription Active Until
        {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}
    </button>
@endif
                </div>
            </div>
        @else
            <div class="card p-5 text-center">
                <div class="card-body">
                    <div class="display-1 brand-accent-text mb-3">🎫</div>
                    <h4 class="classic-serif mb-3">No Active Subscription</h4>
                    <p class="text-secondary mb-4">Subscribe to a membership plan to increase your rental limits and unlock more premium books.</p>
                    <a href="{{ route('membership.plans') }}" class="btn btn-warning px-5 py-3">
                        Browse Plans
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection