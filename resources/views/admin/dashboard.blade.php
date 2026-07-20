@extends('admin.layout')

@section('content')

<div class="mb-5 fade-in-el">
    <h2 class="mb-1">Admin Dashboard</h2>
    <p class="text-secondary">
        Overview of system performance, rentals, subscriptions and revenue.
    </p>
</div>

<div class="row fade-in-el">

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card metric-card p-4 h-100">
            <div class="metric-label mb-2">Total Users</div>
            <h3 class="classic-serif metric-value mb-0">
                {{ $totalUsers }}
            </h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card metric-card p-4 h-100">
            <div class="metric-label mb-2">Total Books</div>
            <h3 class="classic-serif metric-value mb-0">
                {{ $totalBooks }}
            </h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card metric-card p-4 h-100">
            <div class="metric-label mb-2">Active Rentals</div>
            <h3 class="classic-serif metric-value mb-0">
                {{ $activeRentals }}
            </h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card metric-card p-4 h-100">
            <div class="metric-label mb-2">Overdue Rentals</div>
            <h3 class="classic-serif metric-value mb-0 text-danger">
                {{ $overdueRentals }}
            </h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card metric-card p-4 h-100">
            <div class="metric-label mb-2">Active Subscriptions</div>
            <h3 class="classic-serif metric-value mb-0 text-success">
                {{ $activeSubscriptions }}
            </h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card metric-card p-4 h-100">
            <div class="metric-label mb-2">Pending Fines</div>
            <h3 class="classic-serif metric-value mb-0 text-warning">
                {{ $pendingFines }}
            </h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card metric-card p-4 h-100">
            <div class="metric-label mb-2">Fine Revenue</div>
            <h3 class="classic-serif metric-value mb-0">
                ₹{{ number_format($paidFines, 2) }}
            </h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card metric-card p-4 h-100">
            <div class="metric-label mb-2">Total Revenue</div>
            <h3 class="classic-serif metric-value mb-0 text-success">
                ₹{{ number_format($revenue, 2) }}
            </h3>
        </div>
    </div>

</div>

<div class="row">

    {{-- TOP BORROWED BOOKS --}}

    <div class="col-lg-6 mb-4">

        <div class="card h-100">

            <div class="card-header">
                <h5 class="mb-0">
                    Top Borrowed Books
                </h5>
            </div>

            <div class="card-body p-0">

                <table class="table mb-0">

                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Borrows</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($topBorrowedBooks as $book)

                        <tr>

                            <td>
                                {{ $book->title }}
                            </td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $book->total_borrows }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="2" class="text-center">
                                No Data Found
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- PENDING RESERVATIONS --}}

    <div class="col-lg-6 mb-4">

        <div class="card h-100">

            <div class="card-header">
                <h5 class="mb-0">
                    Pending Reservations
                </h5>
            </div>

            <div class="card-body p-0">

                <table class="table mb-0">

                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Waiting Users</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($pendingReservations as $reservation)

                        <tr>

                            <td>
                                {{ $reservation->book->title }}
                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $reservation->waiting_count }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="2" class="text-center">
                                No Pending Reservations
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-lg-6 mb-4">

        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">
                    Monthly Revenue
                </h5>
            </div>

            <div class="card-body">

                <canvas id="revenueChart"></canvas>

            </div>

        </div>

    </div>

    <div class="col-lg-6 mb-4">

        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">
                    Monthly Rentals
                </h5>
            </div>

            <div class="card-body">

                <canvas id="rentalChart"></canvas>

            </div>

        </div>

    </div>

</div>

<div class="row">

<div class="col-lg-6 mb-4">

    <div class="card h-100">

        <div class="card-header">
            <h5 class="mb-0">
                Top Rented Books
            </h5>
        </div>

        <div class="card-body">

            <table class="table">

                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Total Rentals</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($topBooks as $book)

                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>
                            <span class="badge bg-success">
                                {{ $book->total_rentals }}
                            </span>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="2" class="text-center">
                            No Data Found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const months = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec'
];

new Chart(
    document.getElementById('revenueChart'),
    {
        type: 'bar',

        data: {

            labels: months,

            datasets: [{
                label: 'Revenue (₹)',
                data: @json($monthlyRevenue)
            }]
        }
    }
);

new Chart(
    document.getElementById('rentalChart'),
    {
        type: 'line',

        data: {

            labels: months,

            datasets: [{
                label: 'Rentals',
                data: @json($monthlyRentals)
            }]
        }
    }
);

</script>

@endsection