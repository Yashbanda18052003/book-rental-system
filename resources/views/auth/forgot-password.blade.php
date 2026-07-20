<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | BookNest</title>

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
                    Forgot Password
                </h2>

                <p class="text-secondary">
                    Enter your email to receive a password reset link.
                </p>

            </div>

            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif

            <form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-3">
        <label>Email Address <span class="text-danger">*</span></label>

        <input type="email"
               name="email"
               class="form-control"
               placeholder="Enter your email">
               @error('email')
    <small class="text-danger">
        {{ $message }}
    </small>
@enderror
    </div>

    <button class="btn btn-warning w-100 py-2 mb-2">
        Send Reset Link
    </button>

    <a href="{{ route('login') }}"
       class="btn btn-outline-warning w-100">
        Back To Login
    </a>
</form>
        </div>

        <div class="auth-image-side">

            <div class="auth-image-content">

                <h2>BookNest Account Recovery</h2>

                <p>
                    We will send a secure password reset link to your registered email address.
                </p>

            </div>

        </div>

    </div>

</div>

</body>
</html>