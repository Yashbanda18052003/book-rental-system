@extends('admin.layout')

@section('content')

<h2>Books Management</h2>

<div class="d-flex justify-content-between mb-3">


<a href="{{ route('books.create') }}"
   class="btn btn-primary align-self-start">
   Add Book
</a>
<form method="GET"
      action="{{ route('books.index') }}"
      class="d-flex gap-2">

    <input type="text"
           name="search"
           class="form-control"
           placeholder="Search title, author or ISBN"
           value="{{ request('search') }}">

    <button class="btn btn-primary">
        Search
    </button>

</form>

</div>

<table class="table table-bordered table-hover">


<thead>

    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Title</th>
        <th>Author</th>
        <th>ISBN</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Availability</th>
        <th width="180">Action</th>
    </tr>

</thead>

<tbody>

@forelse($books as $book)

    <tr>

        <td>{{ $book->id }}</td>

        <td>

            @if($book->image)

                <img src="{{ asset('uploads/books/'.$book->image) }}"
                     width="70">

            @else

                No Image

            @endif

        </td>

        <td>{{ $book->title }}</td>

        <td>{{ $book->author }}</td>

        <td>{{ $book->isbn }}</td>

        <td>₹{{ $book->rental_price }}</td>

        <td>{{ $book->stock }}</td>

        <td>

            @if($book->stock > 0)

                <span class="badge bg-success">
                    Available
                </span>

            @else

                <span class="badge bg-danger">
                    Out Of Stock
                </span>

            @endif

        </td>

        <td>
    <div class="d-flex gap-2">
        <a href="{{ route('books.edit',$book->id) }}"
           class="btn btn-warning btn-sm">
            Edit
        </a>

        <button
            class="btn btn-danger btn-sm deleteBook"
            data-id="{{ $book->id }}">
            Delete
        </button>
    </div>
</td>

    </tr>

@empty

    <tr>

        <td colspan="9" class="text-center">
            No Books Found
        </td>

    </tr>

@endforelse

</tbody>


</table>

<div class="mt-3">
    {{ $books->links() }}
</div>
@push('scripts')

<script>

$(document).on('click', '.deleteBook', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'Delete Book?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then((result) => {

        if(result.isConfirmed){

            $.ajax({

                url: '/admin/books/' + id,

                type: 'POST',

                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },

                success:function(){

                    Swal.fire({
                        icon:'success',
                        title:'Deleted Successfully'
                    }).then(() => {
                        location.reload();
                    });

                },

                error:function(xhr){

                    console.log(xhr.responseText);

                    Swal.fire({
                        icon:'error',
                        title:'Delete Failed'
                    });

                }

            });

        }

    });

});

let timer;

$('input[name="search"]').on('keyup', function () {

    clearTimeout(timer);

    timer = setTimeout(() => {

        let value = $(this).val();

        if(value.length >= 3 || value.length === 0){
            $(this).closest('form').submit();
        }

    }, 500);

});

</script>

@endpush

@endsection
