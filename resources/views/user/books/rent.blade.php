@extends('user.layout')
@section('content')
<div class="mb-4 fade-in-el">
    <h2>Rent Book</h2>
</div>
<div class="row fade-in-el">
    <!-- Book Details Column -->
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            @if($book->image)
                <div class="book-image-wrapper" style="height: 380px;">
                    <img src="{{ asset('uploads/books/'.$book->image) }}" alt="{{ $book->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @endif
            <div class="card-body">
                <h3 class="classic-serif brand-accent-text mb-2">{{ $book->title }}</h3>
                <h6 class="text-secondary mb-3">by {{ $book->author }}</h6>
                <p class="text-secondary small mb-4">{{ $book->description }}</p>
                
                <div class="d-flex justify-content-between align-items-center p-3 rounded bg-primary bg-opacity-10 border border-primary border-opacity-10">
                    <span class="text-secondary fw-semibold">Price Per Day</span>
                    <span class="h4 brand-accent-text mb-0 fw-bold">₹{{ $book->rental_price }}</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Rental Form Column -->
    <div class="col-md-7 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h4 class="classic-serif mb-0">Select Rental Period</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('rent.store', $book->id) }}" method="POST" class="rentForm"  novalidate>
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Rental Start Date <span class="text-danger">*</span></label>
                        <input type="text"
       id="start_date"
       name="start_date"
       class="form-control"
       placeholder="dd/mm/yy"
       value="{{ old('start_date') }}"
       autocomplete="off">
                        @error('start_date')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Rental End Date <span class="text-danger">*</span></label>
                       <input type="text"
       id="end_date"
       name="end_date"
       class="form-control"
       placeholder="dd/mm/yy"
       value="{{ old('end_date') }}"
       autocomplete="off">
                        @error('end_date')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                        <div class="form-text text-secondary mt-2 small">
                            Maximum rental duration is 15 days. Minimum booking amount is ₹50.
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-warning py-3">
                            Proceed to Payment
                        </button>
                        <a href="{{ route('catalog') }}" class="btn btn-outline-secondary py-2 mt-2">
                            Cancel & Go Back
                        </a>
                    </div>
                </form>
            
        </div>
    </div>
</div>


<script>
$(document).on('submit', '.rentForm', function(e){

    let startDate = $('input[name="start_date"]').val();
    let endDate = $('input[name="end_date"]').val();

    if(startDate === '' || endDate === '')
    {
        e.preventDefault();

        Swal.fire({
            icon: 'error',
            title: 'Missing Dates',
            text: 'Please select both start date and end date.'
        });

        return false;
    }

});
</script>
<script>
$(document).on('submit', '.rentForm', function(e){

    e.preventDefault();

    let form = this;

    Swal.fire({
        title: 'Confirm Rental?',
        text: 'You will be redirected to Stripe payment.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Continue',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if(result.isConfirmed){
            form.submit();
        }

    });

});
</script>

<script>
let endPicker;

flatpickr("#start_date", {
    dateFormat: "Y-m-d",
    minDate: "today",
    maxDate: "{{ date('Y-12-31') }}",
    position: "auto center",

    onChange: function(selectedDates) {

        let start = selectedDates[0];

        let maxEnd = new Date(start);

        maxEnd.setDate(maxEnd.getDate() + 15);

        endPicker.set("minDate", start);
        endPicker.set("maxDate", maxEnd);
    }
});

endPicker = flatpickr("#end_date", {
    dateFormat: "Y-m-d",
    minDate: "today",
    position: "auto center"
});
</script>

@endsection
