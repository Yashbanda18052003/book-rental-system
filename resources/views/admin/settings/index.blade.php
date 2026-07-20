@extends('admin.layout')

@section('content')

<div class="mb-4">
    <h2>BookNest Settings</h2>
    <p class="text-secondary">
        Configure system-wide settings.
    </p>
</div>

<div class="card">

    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.settings.update') }}">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Fine Per Day (₹) <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           name="fine_per_day"
                           class="form-control"
                           value="{{ $setting->fine_per_day }}">

                    @error('fine_per_day')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Default Rental Days <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           name="rental_days"
                           class="form-control"
                           value="{{ $setting->rental_days }}">

                    @error('rental_days')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Contact Email
                    </label>

                    <input type="email"
                           name="contact_email"
                           class="form-control"
                           value="{{ $setting->contact_email }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Contact Phone
                    </label>

                    <input type="text"
                           name="contact_phone"
                           class="form-control"
                           value="{{ $setting->contact_phone }}">

                </div>

            </div>

            <button type="submit"
                    class="btn btn-warning">
                Save Settings
            </button>

        </form>

    </div>

</div>

@endsection