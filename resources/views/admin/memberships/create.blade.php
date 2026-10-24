@extends('admin.layout')

@section('content')

<h2>Add Membership Plan</h2>

<form action="{{ route('plans.store') }}"
      method="POST"
      novalidate>

    @csrf

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           maxlength="20"
                           placeholder="Enter Membership Name"
                           value="{{ old('name') }}">

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Price <span class="text-danger">*</span></label>
                    <input type="number"
                           step="0.01"
                           name="price"
                           class="form-control @error('price') is-invalid @enderror"
                           placeholder="Enter Membership Price"
                           value="{{ old('price') }}">

                    @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Duration <span class="text-danger">*</span></label>
                    <select name="duration"
                            class="form-select @error('duration') is-invalid @enderror">

                        <option value="monthly" {{ old('duration') == 'monthly' ? 'selected' : '' }}>
                            Monthly
                        </option>

                        <option value="annual" {{ old('duration') == 'annual' ? 'selected' : '' }}>
                            Annual
                        </option>

                    </select>

                    @error('duration')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Rental Limit <span class="text-danger">*</span></label>
                    <input type="number"
                           name="rental_limit"
                           class="form-control @error('rental_limit') is-invalid @enderror"
                           placeholder="Enter Rental Limit"
                           value="{{ old('rental_limit') }}">

                    @error('rental_limit')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label">Description <span class="text-secondary small">(optional)</span></label>
                <textarea name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Enter Description"
                          maxlength="100"
                          rows="4">{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button class="btn btn-success">
                Save Plan
            </button>

        </div>
    </div>

</form>

@endsection