@extends('admin.layout')

@section('content')

<div class="custom-breadcrumb fade-in-el">
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <span class="separator">/</span>
    <span class="current">Admin Profile</span>
</div>

<div class="row g-4 fade-in-el">
    
    {{-- Left Column: Admin Summary & Photo Upload --}}
    <div class="col-lg-4">
        <div class="profile-card p-4 text-center">
            
            <div class="profile-avatar-container mb-3">
                @if(auth()->user()->profile_picture_url)
                    <img src="{{ auth()->user()->profile_picture_url }}" 
                         alt="{{ auth()->user()->name }}" 
                         id="admin_avatar_preview_img" 
                         class="profile-avatar-img">
                @else
                    <img src="" 
                         alt="{{ auth()->user()->name }}" 
                         id="admin_avatar_preview_img" 
                         class="profile-avatar-img d-none">
                    <div id="admin_avatar_preview_initial" class="profile-avatar-initial">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif

                <label for="admin_profile_picture_input" class="profile-avatar-upload-btn" title="Upload Profile Picture">
                    <i class="bi bi-camera-fill"></i>
                </label>
            </div>

            <h4 class="mb-1 text-white fw-bold">{{ auth()->user()->name }}</h4>
            <p class="text-secondary small mb-3"><i class="bi bi-envelope-fill me-1"></i> {{ auth()->user()->email }}</p>

            <div class="d-flex justify-content-center gap-2 mb-4">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                    <i class="bi bi-shield-lock-fill me-1"></i> System Admin
                </span>
                <span class="badge bg-secondary text-light px-3 py-2 rounded-pill fw-normal">
                    <i class="bi bi-calendar-event me-1"></i> {{ auth()->user()->created_at ? auth()->user()->created_at->format('M Y') : 'Active' }}
                </span>
            </div>

            <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px dashed var(--border-color);">
                <div class="small text-secondary mb-1"><i class="bi bi-info-circle me-1"></i> <strong>Profile Picture Hint</strong></div>
                <div class="small text-muted" style="font-size: 0.78rem;">Click the camera icon on your photo to upload a new avatar (JPG, PNG, WEBP, max 2MB).</div>
            </div>

        </div>
    </div>

    {{-- Right Column: Edit Profile Form --}}
    <div class="col-lg-8">
        <div class="profile-card p-4">
            
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf

                {{-- Hidden image file input triggered by avatar upload button --}}
                <input type="file" 
                       id="admin_profile_picture_input" 
                       name="profile_picture" 
                       accept="image/*" 
                       class="d-none">

                {{-- Personal Information Section --}}
                <div class="profile-section-title">
                    <i class="bi bi-person-lines-fill"></i> Personal Information
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>
                        @error('name')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" required>
                        </div>
                        @error('phone')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email Address (Read-only)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly disabled style="opacity: 0.7;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-shield-check"></i></span>
                            <input type="text" class="form-control" value="Administrator" readonly disabled style="opacity: 0.7;">
                        </div>
                    </div>
                </div>

                {{-- Password Security Section --}}
                <div class="profile-section-title">
                    <i class="bi bi-shield-lock-fill"></i> Password & Security
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">New Password <small class="text-muted">(Leave blank to keep current)</small></label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="adminNewPassword" class="form-control" placeholder="Minimum 8 characters" maxlength="12">
                            <button type="button" class="password-toggle-btn" onclick="togglePassVisibility('adminNewPassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Confirm New Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="adminConfirmPassword" class="form-control" placeholder="Re-enter new password" maxlength="12">
                            <button type="button" class="password-toggle-btn" onclick="togglePassVisibility('adminConfirmPassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2 border-top border-secondary">
                    <button type="submit" class="btn btn-warning px-4 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
document.getElementById('admin_profile_picture_input')?.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        let file = e.target.files[0];
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Please select an image smaller than 2MB.'
            });
            this.value = '';
            return;
        }

        let reader = new FileReader();
        let form = this.closest('form');
        reader.onload = function(evt) {
            let img = document.getElementById('admin_avatar_preview_img');
            let initial = document.getElementById('admin_avatar_preview_initial');
            if (img) {
                img.src = evt.target.result;
                img.classList.remove('d-none');
            }
            if (initial) initial.classList.add('d-none');

            Swal.fire({
                title: 'Updating Profile Picture...',
                text: 'Saving your new photo.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            form.submit();
        }
        reader.readAsDataURL(file);
    }
});

function togglePassVisibility(inputId, btn) {
    let input = document.getElementById(inputId);
    let icon = btn.querySelector('i');
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

@endsection