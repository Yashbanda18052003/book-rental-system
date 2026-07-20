@extends('admin.layout')

@section('content')

<a href="{{ route('reports.export.rentals') }}"
   class="btn btn-success btn-sm">
   Export Rentals
</a>

<a href="{{ route('reports.export.subscriptions') }}"
   class="btn btn-primary btn-sm">
   Export Subscriptions
</a>

<a href="{{ route('reports.export.fines') }}"
   class="btn btn-warning btn-sm">
   Export Fines
</a>

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
