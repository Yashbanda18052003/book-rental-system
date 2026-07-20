@extends('user.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in-el">
    <h2>My Rentals</h2>
    <form method="GET"
      action="{{ route('my.rentals') }}"
      class="mb-3">

    <input type="text"
           name="search"
           class="form-control"
           placeholder="🔍 Search Book Title"
           value="{{ request('search') }}">

</form>
</div>
<div class="card fade-in-el">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($rentals as $rental)
                    <tr>
                        <td class="fw-bold classic-serif">{{ $rental->book->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($rental->start_date)->format('d M, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($rental->end_date)->format('d M, Y') }}</td>
                        <td class="brand-accent-text fw-bold">₹{{ $rental->amount }}</td>
                        <td>
                            @if($rental->status == 'active')
    <span class="badge bg-success">
        Active
    </span>
@elseif($rental->status == 'returned')
    <span class="badge bg-primary">
        Returned
    </span>
@elseif($rental->status == 'pending')
    <span class="badge bg-warning">
        Pending
    </span>
@endif
                        </td>
                        <td>
@if($rental->status == 'pending')
    <a href="{{ route('stripe.checkout', $rental->id) }}"
       class="btn btn-warning btn-sm">
       Pay Now
    </a>

@elseif($rental->status == 'active')
    <span class="badge bg-success">
        Paid
    </span>

@elseif($rental->status == 'returned')
    <span class="badge bg-primary">
        Completed
    </span>

@endif
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-secondary">
                            No Rentals Found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{ $rentals->links() }}

<script>

let timer;

$('input[name="search"]').on('keyup', function(){

    clearTimeout(timer);

    timer = setTimeout(() => {

        $(this).closest('form').submit();

    }, 500);

});

</script>
@endsection