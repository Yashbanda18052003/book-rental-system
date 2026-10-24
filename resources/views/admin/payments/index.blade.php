@extends('admin.layout')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="mb-3">
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle d-inline-flex align-items-center gap-1"
                type="button"
                id="exportDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="bi bi-download"></i> Export Report
        </button>
        <ul class="dropdown-menu shadow" aria-labelledby="exportDropdown">
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2"
                   href="{{ route('admin.payments.export', ['type' => 'excel']) }}">
                    <i class="bi bi-file-earmark-excel text-success"></i> Export Excel (.xlsx)
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2"
                   href="{{ route('admin.payments.export', ['type' => 'pdf']) }}">
                    <i class="bi bi-file-earmark-pdf text-danger"></i> Export PDF (.pdf)
                </a>
            </li>
        </ul>
    </div>
</div>

<h2>Payment Management</h2>
<div class="d-flex justify-content-between mb-3">

    <form method="GET"
          action="{{ route('admin.payments') }}"
          class="d-flex gap-2">

        <input type="text"
               name="search"
               class="form-control"
               placeholder="Search User, Payment Type or Status"
               value="{{ request('search') }}">

        <button class="btn btn-primary">
            Search
        </button>

    </form>

</div>
<table class="table table-bordered">

    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Payment Type</th>
            <th>Details</th>
            <th>Amount</th>
            <th>Stripe Payment ID</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>

    <tbody>

    @forelse($payments as $payment)

        <tr>

            <td>{{ $payment->id }}</td>

            <td>
                {{ $payment->user->name ?? 'N/A' }}
            </td>

            <td>

                @if($payment->payment_type == 'rental')

                    <span class="badge bg-primary">
                        Rental
                    </span>

                @elseif($payment->payment_type == 'subscription')

                    <span class="badge bg-success">
                        Subscription
                    </span>

                @elseif($payment->payment_type == 'fine')

                    <span class="badge bg-danger">
                        Fine
                    </span>

                @endif

            </td>

            <td>

                @if($payment->payment_type == 'rental')

                    Rental #{{ $payment->rental_id }}

                    @if($payment->rental && $payment->rental->book)
                        <br>
                        <small>
                            {{ $payment->rental->book->title }}
                        </small>
                    @endif

                @elseif($payment->payment_type == 'subscription')

                    @if($payment->subscription && $payment->subscription->plan)
                        {{ $payment->subscription->plan->name }}
                    @else
                        Subscription
                    @endif

                @elseif($payment->payment_type == 'fine')

                    Fine #{{ $payment->fine_id }}

                @endif

            </td>

            <td>
                ₹{{ $payment->amount }}
            </td>

            <td>
                {{ $payment->stripe_payment_id ?? '-' }}
            </td>

            <td>

                @if($payment->status == 'paid')

                    <span class="badge bg-success">
                        Paid
                    </span>

                @elseif($payment->status == 'failed')

                    <span class="badge bg-danger">
                        Failed
                    </span>

                @else

                    <span class="badge bg-warning">
                        Pending
                    </span>

                @endif

            </td>

            <td>
                {{ $payment->created_at->format('d-m-Y H:i') }}
            </td>

        </tr>

    @empty

        <tr>
            <td colspan="8" class="text-center">
                No Payments Found
            </td>
        </tr>

    @endforelse

    </tbody>

</table>

<div class="mt-3">
    {{ $payments->links() }}
</div>

<script>

let timer;

$('input[name="search"]').on('keyup', function () {

    clearTimeout(timer);

    timer = setTimeout(() => {

        $(this).closest('form').submit();

    }, 500);

});

</script>

@endsection