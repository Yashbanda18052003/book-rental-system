@extends('admin.layout')

@section('content')

<h2>Rental Management</h2>
<div class="d-flex justify-content-between mb-3">

    <form method="GET"
          action="{{ route('admin.rentals') }}"
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
            <th>Start</th>
            <th>End</th>
            <th>Amount</th>
            <th>Fine</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

    @forelse($rentals as $rental)

        <tr>

            <td>{{ $rental->user->name }}</td>

            <td>{{ $rental->book->title }}</td>

            <td>{{ $rental->start_date }}</td>

            <td>{{ $rental->end_date }}</td>

            <td>₹{{ $rental->amount }}</td>

            <td>₹{{ $rental->fine }}</td>

            <td>{{ ucfirst($rental->status) }}</td>

            <td>

                @if($rental->status == 'active' || $rental->status == 'overdue')
            
                    <form action="{{ route('rentals.return', $rental->id) }}"
                          method="POST">
            
                        @csrf
            
                        <button type="submit"
                                class="btn btn-success btn-sm">
                            Return Book
                        </button>
            
                    </form>
            
                @elseif($rental->status == 'returned')
            
                    <span class="badge bg-success">
                        Returned
                    </span>
            
                @else
            
                    <span class="badge bg-secondary">
                        {{ ucfirst($rental->status) }}
                    </span>
            
                @endif
            
            </td>
        </tr>

         @empty

        <tr>
            <td colspan="8" class="text-center">
                No Rentals Found
            </td>
        </tr>

    @endforelse

   

    </tbody>

</table>

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