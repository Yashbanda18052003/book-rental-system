@extends('user.layout')
@section('content')
<div class="mb-5 fade-in-el">
    <h2 class="mb-1">Welcome back, <span class="brand-accent-text">{{ auth()->user()->name }}</span></h2>
    <p class="text-secondary">Manage your rentals, subscriptions, and explore new books in your library.</p>
</div>
<div class="row fade-in-el">
    <div class="col-md-4 mb-4">
        <div class="card metric-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="metric-label mb-2">Active rentals</div>
                <h3 class="classic-serif mb-4">My Rentals</h3>
            </div>
            <a href="{{ route('my.rentals') }}" class="btn btn-warning w-100">
               View Rentals
            </a>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card metric-card p-4 h-100 d-flex flex-column justify-content-between" style="border-left-color: var(--accent-indigo) !important;">
            <div>
                <div class="metric-label mb-2">Explore Literature</div>
                <h3 class="classic-serif mb-4">Book Catalog</h3>
            </div>
            <a href="{{ route('catalog') }}" class="btn btn-primary w-100">
               Browse Books
            </a>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card metric-card p-4 h-100 d-flex flex-column justify-content-between" style="border-left-color: var(--accent-emerald) !important;">
            <div>
                <div class="metric-label mb-2">Waiting list status</div>
                <h3 class="classic-serif mb-4">Reservations</h3>
            </div>
            <a href="{{ route('my.reservations') }}" class="btn btn-success w-100">
               View Reservations
            </a>
        </div>
    </div>
</div>
@endsection