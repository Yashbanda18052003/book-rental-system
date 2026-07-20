@extends('admin.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 fade-in-el">
    <h2>Admin Profile</h2>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card fade-in-el">
    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.profile.update') }}">

            @csrf

            <h5 class="mb-3">
                Personal Information
            </h5>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Full Name <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ auth()->user()->name }}">

                    @error('name')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                    @enderror

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Email Address
                    </label>

                    <input type="email"
                           class="form-control"
                           value="{{ auth()->user()->email }}"
                           readonly>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">
                        Phone Number <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="{{ auth()->user()->phone }}">

                    @error('phone')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                    @enderror

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">
                        Role
                    </label>

                    <input type="text"
                           class="form-control"
                           value="Administrator"
                           readonly>

                </div>

            </div>

            <hr>

            <h5 class="mb-3">
                Change Password
            </h5>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        New Password
                    </label>

                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Leave blank to keep current password"
                           maxlength="8">

                    @error('password')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                    @enderror

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Confirm Password
                    </label>

                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           maxlength="8">

                </div>

            </div>

            <button type="submit"
                    class="btn btn-warning">
                Update Profile
            </button>

        </form>

    </div>
</div>

@endsection