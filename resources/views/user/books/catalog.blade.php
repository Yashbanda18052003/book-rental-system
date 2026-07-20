@extends('user.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 fade-in-el">

    <div>
        <h2>Available Books</h2>
        <span class="text-secondary">
            {{ $books->total() }} literary works cataloged
        </span>
    </div>

    <form method="GET"
      action="{{ route('catalog') }}"
      id="searchForm">

    <input type="text"
           name="search"
           class="form-control"
           placeholder="Search title or author..."
           value="{{ request('search') }}">

</form>

</div>

<div class="row fade-in-el">

@foreach($books as $book)

<div class="col-lg-3 col-md-4 col-sm-6 mb-4">

    <div class="card book-catalog-card h-100">

        <div class="book-image-wrapper">

            @if($book->image)

                <img src="{{ asset('uploads/books/'.$book->image) }}"
                     alt="{{ $book->title }}">

            @else

                <div class="d-flex align-items-center justify-content-center h-100 bg-dark text-secondary">
                    <span class="small">No Cover Art</span>
                </div>

            @endif

            @if($book->stock > 0)

                <span class="book-badge">
                    Available
                </span>

            @else

                <span class="book-badge out-of-stock">
                    Out of Stock
                </span>

            @endif

        </div>

        <div class="card-body d-flex flex-column justify-content-between">

            <div>

                <h5 class="card-title classic-serif text-truncate mb-1"
                    title="{{ $book->title }}">
                    {{ $book->title }}
                </h5>

                <p class="text-secondary small mb-3">
                    by {{ $book->author }}
                </p>

            </div>

            <div class="mt-auto">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <span class="brand-accent-text fw-bold">
                        ₹{{ $book->rental_price }}
                        <span class="text-secondary fw-normal small">
                            /day
                        </span>
                    </span>

                    <span class="small text-secondary">
                        Stock: {{ $book->stock }}
                    </span>

                </div>

                @if($book->stock > 0)

                    <a href="{{ route('rent.form', $book->id) }}"
                       class="btn btn-warning w-100">
                        Rent Book
                    </a>

                @else

                    <form action="{{ route('reserve.book', $book->id) }}"
                          method="POST"
                          class="reserveForm">

                        @csrf

                        <button type="submit"
                                class="btn btn-info w-100">
                            Reserve Book
                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>

@endforeach

</div>

<div class="mt-4">
    {{ $books->links() }}
</div>


<script>

let timer;

$('input[name="search"]').on('keyup', function () {

    clearTimeout(timer);

    let value = $(this).val();

    timer = setTimeout(() => {

        if(value.length >= 2 || value.length === 0)
        {
            $('#searchForm').submit();
        }

    }, 500);

});

</script>

@endsection