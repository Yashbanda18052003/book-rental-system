@extends('admin.layout')

@section('content')

<div class="d-flex gap-2 mb-3">

    {{-- Rentals Export Dropdown --}}
    <div class="dropdown">
        <button class="btn btn-success btn-sm dropdown-toggle d-inline-flex align-items-center gap-1"
                type="button"
                data-bs-toggle="dropdown">
            <i class="bi bi-download"></i> Export Rentals
        </button>
        <ul class="dropdown-menu shadow">
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2"
                   href="{{ route('reports.export.rentals', ['type' => 'excel']) }}">
                    <i class="bi bi-file-earmark-excel text-success"></i> Excel (.xlsx)
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2"
                   href="{{ route('reports.export.rentals', ['type' => 'pdf']) }}">
                    <i class="bi bi-file-earmark-pdf text-danger"></i> PDF (.pdf)
                </a>
            </li>
        </ul>
    </div>

    {{-- Subscriptions Export Dropdown --}}
    <div class="dropdown">
        <button class="btn btn-primary btn-sm dropdown-toggle d-inline-flex align-items-center gap-1"
                type="button"
                data-bs-toggle="dropdown">
            <i class="bi bi-download"></i> Export Subscriptions
        </button>
        <ul class="dropdown-menu shadow">
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2"
                   href="{{ route('reports.export.subscriptions', ['type' => 'excel']) }}">
                    <i class="bi bi-file-earmark-excel text-success"></i> Excel (.xlsx)
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2"
                   href="{{ route('reports.export.subscriptions', ['type' => 'pdf']) }}">
                    <i class="bi bi-file-earmark-pdf text-danger"></i> PDF (.pdf)
                </a>
            </li>
        </ul>
    </div>

    {{-- Fines Export Dropdown --}}
    <div class="dropdown">
        <button class="btn btn-warning btn-sm dropdown-toggle d-inline-flex align-items-center gap-1"
                type="button"
                data-bs-toggle="dropdown">
            <i class="bi bi-download"></i> Export Fines
        </button>
        <ul class="dropdown-menu shadow">
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2"
                   href="{{ route('reports.export.fines', ['type' => 'excel']) }}">
                    <i class="bi bi-file-earmark-excel text-success"></i> Excel (.xlsx)
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2"
                   href="{{ route('reports.export.fines', ['type' => 'pdf']) }}">
                    <i class="bi bi-file-earmark-pdf text-danger"></i> PDF (.pdf)
                </a>
            </li>
        </ul>
    </div>

</div>

<h2 class="mb-4">Reports Center</h2>

<ul class="nav nav-tabs mb-4">

<li class="nav-item">
    <button class="nav-link active"
            data-bs-toggle="tab"
            data-bs-target="#rentals">
        Rental Reports
    </button>
</li>

<li class="nav-item">
    <button class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#subscriptions">
        Subscription Reports
    </button>
</li>

<li class="nav-item">
    <button class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#fines">
        Fine Reports
    </button>
</li>


</ul>

<div class="tab-content">


{{-- RENTALS --}}
<div class="tab-pane fade show active" id="rentals">

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Book</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>

        @forelse($rentals as $rental)

            <tr>
                <td>{{ $rental->user->name }}</td>
                <td>{{ $rental->book->title }}</td>
                <td>{{ ucfirst($rental->status) }}</td>
                <td>₹{{ $rental->amount }}</td>
                <td>{{ $rental->created_at->format('d M Y') }}</td>
            </tr>

        @empty

            <tr>
                <td colspan="5" class="text-center">
                    No Rental Records Found
                </td>
            </tr>

        @endforelse

        </tbody>
    </table>

    {{ $rentals->links() }}

</div>

{{-- SUBSCRIPTIONS --}}
<div class="tab-pane fade" id="subscriptions">

    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>User</th>
                <th>Plan</th>
                <th>Price</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>

        <tbody>

        @forelse($subscriptions as $subscription)

            <tr>

                <td>{{ $subscription->user->name }}</td>

                <td>{{ $subscription->plan->name }}</td>

                <td>₹{{ $subscription->plan->price }}</td>

                <td>
                    @if($subscription->status == 'active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    @endif
                </td>

                <td>{{ $subscription->start_date }}</td>

                <td>{{ $subscription->end_date }}</td>

            </tr>

        @empty

            <tr>
                <td colspan="6" class="text-center">
                    No Subscription Records Found
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

    {{ $subscriptions->links() }}

</div>

{{-- FINES --}}
<div class="tab-pane fade" id="fines">

    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>User</th>
                <th>Book</th>
                <th>Late Days</th>
                <th>Fine Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>

        @forelse($fines as $fine)

            <tr>

                <td>{{ $fine->user->name }}</td>

                <td>{{ $fine->rental->book->title ?? 'N/A' }}</td>

                <td>{{ $fine->late_days }}</td>

                <td>₹{{ $fine->fine_amount }}</td>

                <td>
                    @if($fine->status == 'paid')
                        <span class="badge bg-success">
                            Paid
                        </span>
                    @else
                        <span class="badge bg-danger">
                            Pending
                        </span>
                    @endif
                </td>

                <td>{{ $fine->created_at->format('d M Y') }}</td>

            </tr>

        @empty

            <tr>
                <td colspan="6" class="text-center">
                    No Fine Records Found
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

    {{ $fines->links() }}

</div>


</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    let activeTab = localStorage.getItem('reportsActiveTab');

    if (activeTab) {
        let trigger = document.querySelector(
            '[data-bs-target="' + activeTab + '"]'
        );

        if (trigger) {
            new bootstrap.Tab(trigger).show();
        }
    }

    document.querySelectorAll(
        '[data-bs-toggle="tab"]'
    ).forEach(tab => {

        tab.addEventListener('shown.bs.tab', function (e) {

            localStorage.setItem(
                'reportsActiveTab',
                e.target.dataset.bsTarget
            );

        });

    });

});
</script>

@endsection
