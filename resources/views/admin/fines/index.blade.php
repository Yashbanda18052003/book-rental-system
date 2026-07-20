@extends('admin.layout')

@section('content')

<h2>Fine Management</h2>

<div class="d-flex justify-content-between mb-3">

    <form method="GET"
          action="{{ route('admin.fines') }}"
          class="d-flex gap-2">

        <input type="text"
               name="search"
               class="form-control"
               placeholder="Search User or Status"
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
            <th>Late Days</th>
            <th>Fine Amount</th>
            <th>Status</th>
            <th>Created</th>
        </tr>
    </thead>

    <tbody>

    @forelse($fines as $fine)

        <tr>

            <td>
                {{ $fine->user->name }}
            </td>

            <td>
                {{ $fine->rental->book->title ?? 'N/A' }}
            </td>

            <td>
                {{ $fine->late_days }}
            </td>

            <td>
                ₹{{ $fine->fine_amount }}
            </td>

            <td>

                @if($fine->status == 'pending')

                    <span class="badge bg-warning">
                        Waiting For User Payment
                    </span>

                @else

                    <span class="badge bg-success">
                        Paid
                    </span>

                @endif

            </td>

            <td>
                {{ $fine->created_at->format('d M Y') }}
            </td>

        </tr>

    @empty

        <tr>

            <td colspan="6"
                class="text-center">

                No Fines Found

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

<div class="mt-3">
    {{ $fines->links() }}
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