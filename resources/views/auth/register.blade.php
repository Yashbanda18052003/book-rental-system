<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | BookNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon"
      type="image/png"
      href="{{ asset('images/booknest-logo.png') }}">
</head>
<body class="auth-page">
<div class="container fade-in-el">
    <div class="auth-split-container">
        
        <!-- Form Side -->
        <div class="auth-form-side">
            <div class="text-center mb-4">
                 <img src="{{ asset('images/booknest-logo.png') }}"
         alt="BookNest"
         width="100"
         height="100"
         class="mb-3 rounded-circle border border-3 border-warning shadow">

               <h2 class="brand-accent-text mb-1">Join BookNest</h2>
<p class="text-secondary">Create your account and start your reading journey</p>
            </div>
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="mb-3">
                    <label>Full Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           maxlength="10"
                           placeholder="Enter Name"
                           >
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label>Email Address <span class="text-danger">*</span></label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           maxlength="50"
                           autocomplete="off"
                           placeholder="Enter Email Address"
                           >
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
    <label>Phone Number <span class="text-danger">*</span></label>

    <input type="text"
           name="phone"
           class="form-control"
           value="{{ old('phone') }}"
           placeholder="+919876543210"
           >

    @error('phone')
        <small class="text-danger">
            {{ $message }}
        </small>
    @enderror
</div>
                <div class="mb-3">
                    <label>Password (Minimum 8 Characters) <span class="text-danger">*</span></label>
                    <input type="password"
                     name="password"
                     maxlength="8"
                     class="form-control"
                           autocomplete="off"
                           placeholder="Enter Password"
                           >
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-4">
                    <label>Confirm Password <span class="text-danger">*</span></label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           maxlength="8"
                           placeholder="Re-Type Password"
                           >
                    @error('password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-warning w-100 py-2">
    Create Account
</button>
                    
                    <div class="text-center mt-3">
                        <span class="text-secondary small">Already have an account?</span>
                        <a href="{{ route('login') }}" class="brand-accent-text text-decoration-none small fw-bold ms-1">
                            Login
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <!-- Image Illustration Side -->
        <div class="auth-image-side">
            <div class="auth-image-content">
               <h2>Become Part of BookNest</h2>
                <p>Register to rent premium publications, reserve popular releases, and manage your custom reading dashboard easily.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>