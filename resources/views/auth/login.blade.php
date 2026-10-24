<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | BookNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
               <h2 class="brand-accent-text mb-1">Welcome to BookNest</h2>
<p class="text-secondary">Sign in to access your reading dashboard</p>
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label>Email Address <span class="text-danger">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-envelope input-field-icon"></i>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            maxlength="50"
                            autocomplete="off"
                            placeholder="Enter Email Address"
                        >
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-4">
                    <label>Password <span class="text-danger">*</span></label>
                    <div class="input-icon-wrapper password-wrapper">
                        <i class="bi bi-lock input-field-icon"></i>
                        <input
                            type="password"
                            name="password"
                            id="loginPassword"
                            class="form-control"
                            autocomplete="off"
                            placeholder="Enter Password"
                            maxlength="12"
                        >
                        <button class="password-toggle-btn" type="button" onclick="togglePasswordVisibility('loginPassword', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="text-end mb-3">
    <a href="{{ route('password.request') }}"
       class="text-decoration-none">
        Forgot Password?
    </a>
</div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning w-100 py-2">
    Login
</button>
                    
                    <div class="text-center mt-3">
                        <span class="text-secondary small">Don't have an account?</span>
                        <a href="{{ route('register') }}" class="brand-accent-text text-decoration-none small fw-bold ms-1">
                            Register Here
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <!-- Image Illustration Side -->
        <div class="auth-image-side">
            <div class="auth-image-content">
                <h2>Welcome to BookNest</h2>
                <p>Access thousands of classic works, contemporary fiction, and technical manuals instantly. Rent or reserve your next adventure today.</p>
            </div>
        </div>
    </div>
</div>
<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
</body>
</html>