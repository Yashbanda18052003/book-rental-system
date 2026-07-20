@extends('user.layout')

@section('content')

<h2>My Fines</h2>

<div class="row mb-3">

    <div class="col-md-4 ms-auto">

        <form method="GET"
              action="{{ route('my.fines') }}">

            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="🔍 Search Book"
                   value="{{ request('search') }}">

        </form>

    </div>

</div>

<table class="table table-bordered">

    <thead>
        <tr>
            <th>Book</th>
            <th>Late Days</th>
            <th>Fine Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

    @forelse($fines as $fine)

        <tr>
            <td>{{ $fine->rental->book->title ?? 'N/A' }}</td>

            <td>{{ $fine->late_days }}</td>

            <td>₹{{ $fine->fine_amount }}</td>

            <td>
                {{ ucfirst($fine->status) }}
            </td>

            <td>

                @if($fine->status == 'pending')

                    <a href="{{ route('fine.checkout', $fine->id) }}"
                       class="btn btn-warning btn-sm">
                       Pay Fine
                    </a>

                @else

                    <span class="badge bg-success">
                        Paid
                    </span>

                @endif

            </td>

        </tr>

    @empty

        <tr>
            <td colspan="5" class="text-center">
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