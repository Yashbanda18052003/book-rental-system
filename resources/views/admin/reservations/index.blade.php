@extends('admin.layout')

@section('content')

<h2>Reservation Management</h2>

<div class="d-flex justify-content-between mb-3">

    <form method="GET"
          action="{{ route('admin.reservations') }}"
          class="d-flex gap-2">

        <input type="text"
               name="search"
               class="form-control"
               placeholder="Search User, Book or Status"
               value="{{ request('search') }}">

        <button class="btn btn-primary">
            Search
        </button>

    </form>

</div>

<table class="table table-bordered">

    <thead>
        <tr>
            <th>User</th>
            <th>Book</th>
            <th>Queue Position</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

    @forelse($reservations as $reservation)

        <tr>

            <td>{{ $reservation->user->name }}</td>

            <td>{{ $reservation->book->title }}</td>

            <td>{{ $reservation->queue_position }}</td>

            <td>{{ ucfirst($reservation->status) }}</td>

            <td>

                @if($reservation->status == 'waiting')

                    <form action="{{ route('reservations.assign', $reservation->id) }}"
                          method="POST">

                        @csrf

                        <button class="btn btn-success btn-sm">
                            Assign
                        </button>

                    </form>

                @else

                    <span class="badge bg-primary">
                        Assigned
                    </span>

                @endif

            </td>

        </tr>

    @empty

        <tr>
            <td colspan="5" class="text-center">
                No Reservations Found
            </td>
        </tr>

    @endforelse

    </tbody>

</table>

<div class="mt-3">
    {{ $reservations->links() }}
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