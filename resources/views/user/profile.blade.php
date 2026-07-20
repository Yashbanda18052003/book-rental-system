@extends('user.layout')

@section('content')

<div class="card shadow-sm">

    <div class="card-header">
        <h4 class="mb-0">My Profile</h4>
    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('profile.update') }}">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Name <span class="text-danger">*</span>
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

            <div class="mb-3">

                <label class="form-label">
                    Email
                </label>

                <input type="email"
                       class="form-control"
                       value="{{ auth()->user()->email }}"
                       readonly>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Phone <span class="text-danger">*</span>
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

            <hr>

            <h5>Change Password</h5>

            <div class="mb-3">

                <label class="form-label">
                    New Password
                </label>

                <input type="password"
                       name="password"
                       class="form-control"
                       maxlength="8">

                @error('password')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Confirm Password
                </label>

                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       maxlength="8">

            </div>

            <button type="submit"
                    class="btn btn-warning">
                Update Profile
            </button>

        </form>

    </div>

</div>

@endsection