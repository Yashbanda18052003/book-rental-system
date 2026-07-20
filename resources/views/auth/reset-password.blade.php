<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | BookNest</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon"
      type="image/png"
      href="{{ asset('images/booknest-logo.png') }}">
</head>

<body class="auth-page">

<div class="container fade-in-el">

    <div class="auth-split-container">

        <div class="auth-form-side">

            <div class="text-center mb-4">

                <img src="{{ asset('images/booknest-logo.png') }}"
                     alt="BookNest"
                     width="100"
                     height="100"
                     class="mb-3 rounded-circle border border-3 border-warning shadow">

                <h2 class="brand-accent-text">
                    Reset Password
                </h2>

                <p class="text-secondary">
                    Create your new password.
                </p>

            </div>

            <form method="POST"
                  action="{{ route('password.update') }}">

                @csrf

                <input type="hidden"
                       name="token"
                       value="{{ $token }}">

                <div class="mb-3">

                    <label>Email Address <span class="text-danger">*</span></label>

                    <input type="email"
       name="email"
       class="form-control">

@error('email')
    <small class="text-danger">
        {{ $message }}
    </small>
@enderror

                </div>

                <div class="mb-3">

                    <label>New Password <span class="text-danger">*</span></label>

                    <input type="password"
       name="password"
       class="form-control"
       maxlength="8">
       <small class="text-secondary">
Minimum 8 characters
</small>

@error('password')
    <small class="text-danger">
        {{ $message }}
    </small>
@enderror

                </div>

                <div class="mb-3">

                    <label>Confirm Password <span class="text-danger">*</span></label>

                    <input type="password"
       name="password_confirmation"
       class="form-control"
       maxlength="8">

                </div>

                <button class="btn btn-warning w-100 py-2 mb-2">
    Reset Password
</button>

<a href="{{ route('login') }}"
   class="btn btn-outline-warning w-100">
    Back To Login
</a>

            </form>

        </div>

        <div class="auth-image-side">

            <div class="auth-image-content">

                <h2>BookNest Security</h2>

                <p>
                    Protect your account by using a strong password.
                </p>

            </div>

        </div>

    </div>

</div>

</body>
</html>