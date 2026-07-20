@extends('admin.layout')

@section('content')

<h2>Edit Book</h2>

<form action="{{ route('books.update', $book->id) }}"
      method="POST"
      enctype="multipart/form-data"
      novalidate>

@csrf
@method('PUT')

<div class="card shadow-sm">
    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       maxlength="50"
                       placeholder="Enter Book Title"
                       value="{{ old('title', $book->title) }}">

                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Author <span class="text-danger">*</span></label>
                <input type="text"
                       name="author"
                       class="form-control @error('author') is-invalid @enderror"
                       maxlength="30"
                       placeholder="Enter Book Author"
                       value="{{ old('author', $book->author) }}">

                @error('author')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">ISBN <span class="text-danger">*</span></label>
                <input type="text"
                       name="isbn"
                       class="form-control @error('isbn') is-invalid @enderror"
                       maxlength="20"
                       placeholder="Enter Book ISBN Number"
                       value="{{ old('isbn', $book->isbn) }}">

                @error('isbn')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Rental Price <span class="text-danger">*</span></label>
                <input type="number"
                       name="rental_price"
                       class="form-control @error('rental_price') is-invalid @enderror"
                       step="0.01"
                       placeholder="Enter Book Rental Price"
                       value="{{ old('rental_price', $book->rental_price) }}">

                @error('rental_price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">Stock <span class="text-danger">*</span></label>
                <input type="number"
                       name="stock"
                       class="form-control @error('stock') is-invalid @enderror"
                       placeholder="Enter Book Stock"
                       value="{{ old('stock', $book->stock) }}">

                @error('stock')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Book Image <span class="text-secondary small">(optional — leave blank to keep current)</span></label>
                <input type="file"
                       name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">

                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                @if($book->image)
                    <div class="mt-2">
                        <img src="{{ asset('uploads/books/'.$book->image) }}"
                             width="80"
                             class="rounded border">
                        <span class="text-secondary small ms-2">Current image</span>
                    </div>
                @endif
            </div>

        </div>

        <div class="mb-3">
            <label class="form-label">Description <span class="text-secondary small">(optional)</span></label>

            <textarea name="description"
                      class="form-control @error('description') is-invalid @enderror"
                      maxlength="200"
                      placeholder="Enter Book Description"
                      rows="4">{{ old('description', $book->description) }}</textarea>

            @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button class="btn btn-success">
            Update Book
        </button>

    </div>
</div>

</form>

@endsection