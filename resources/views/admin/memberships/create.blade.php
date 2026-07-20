@extends('admin.layout')

@section('content')

<h2>Add Membership Plan</h2>

<form action="{{ route('plans.store') }}"
      method="POST">

    @csrf

    <div class="mb-3">
        <label>Name <span class="text-danger">*</span></label>
        <input type="text"
               name="name"
               class="form-control"
               maxlength="20"
               placeholder="Enter Membership Name"
                value="{{ old('name') }}">
               @error('name')
        <span class="text-danger">
            {{ $message }}
        </span>
    @enderror
    </div>

    <div class="mb-3">
        <label>Price <span class="text-danger">*</span></label>
        <input type="number"
               step="0.01"
               name="price"
               class="form-control"
               maxlength="50"
               placeholder="Enter Membership Price"
                value="{{ old('price') }}">
               @error('price')
        <span class="text-danger">
            {{ $message }}
        </span>
    @enderror
    </div>

    <div class="mb-3">
        <label>Duration <span class="text-danger">*</span></label>

        <select name="duration"
                class="form-control">

            <option value="monthly"  {{ old('duration') == 'monthly' ? 'selected' : '' }}>
                Monthly
            </option>

            <option value="annual" {{ old('duration') == 'annual' ? 'selected' : '' }}>
                Annual
            </option>

        </select>
        @error('select')
        <span class="text-danger">
            {{ $message }}
        </span>
    @enderror
    </div>

    <div class="mb-3">
        <label>Rental Limit <span class="text-danger">*</span></label>

        <input type="number"
               name="rental_limit"
               class="form-control"
               maxlength="20"
               placeholder="Enter Rental Limit"
                value="{{ old('rental_limit') }}">
               @error('rental_limit')
        <span class="text-danger">
            {{ $message }}
        </span>
    @enderror
    </div>

    <div class="mb-3">
        <label>Description <span class="text-secondary small">(optional)</span></label>

        <textarea name="description"
                  class="form-control"
                  placeholder="Enter Description"
                  maxlength="100">{{ old('description') }}</textarea>
                  @error('description')
        <span class="text-danger">
            {{ $message }}
        </span>
    @enderror
    </div>

    <button class="btn btn-success">
        Save Plan
    </button>

</form>

@endsection