@extends('user.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in-el">
    <h2>My Reservations</h2>

    <form method="GET" action="{{ route('my.reservations') }}" class="mb-3">
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
                        <th>Queue Position</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <td class="fw-bold classic-serif">{{ $reservation->book->title }}</td>
                        <td>
                            <span class="badge bg-primary px-3 py-2">Position #{{ $reservation->queue_position }}</span>
                        </td>
                        <td>
                            @if($reservation->status == 'waiting')
                                <span class="badge bg-warning text-dark">{{ ucfirst($reservation->status) }}</span>
                            @elseif($reservation->status == 'assigned')
                                <span class="badge bg-success">{{ ucfirst($reservation->status) }}</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($reservation->status == 'assigned')
                                <a href="{{ route('rent.form', $reservation->book->id) }}" class="btn btn-warning btn-sm">Pay Now</a>
                            @elseif($reservation->status == 'waiting')
                                <span class="text-secondary">-</span>
                            @else
                                <span class="badge bg-primary">Completed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-secondary">
                            No Reservations Found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{ $reservations->links() }}

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