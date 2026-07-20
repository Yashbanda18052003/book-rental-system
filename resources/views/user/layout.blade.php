<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookNest</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/booknest-logo.png') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="position: relative; z-index: 1050;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('images/booknest-logo.png') }}"
                 alt="BookNest"
                 width="40"
                 height="40"
                 class="rounded-circle me-2">
            <span class="fw-bold">BookNest</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar" aria-controls="userNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="userNavbar">
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('catalog') }}">Books</a>
                <a class="nav-link" href="{{ route('my.rentals') }}">My Rentals</a>
                <a class="nav-link" href="{{ route('my.reservations') }}">My Reservations</a>
                <a class="nav-link" href="{{ route('membership.plans') }}">Membership Plans</a>
                <a class="nav-link" href="{{ route('my.subscription') }}">My Subscription</a>
                <a class="nav-link" href="{{ route('my.fines') }}">My Fines</a>
                <a class="nav-link" href="{{ route('profile') }}">My Profile</a>
            </div>

            @php
                $unreadCount   = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                $notifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(10)->get();
            @endphp

            {{-- Notification Bell --}}
            <div class="dropdown me-2">
                <button class="btn btn-outline-light position-relative"
                        type="button"
                        id="notifDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="bi bi-bell-fill"></i>
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifBadge">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow"
                    style="min-width:340px; max-height:420px; overflow-y:auto;"
                    id="notifList">
                    <li class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom">
                        <strong>Notifications</strong>
                        @if($unreadCount > 0)
                            <a href="javascript:void(0)" id="markAllRead" class="text-primary small">Mark all as read</a>
                        @endif
                    </li>
                    @forelse($notifications as $notif)
                        <li>
                            <a href="{{ $notif->url ?? '#' }}"
                               class="dropdown-item py-2 {{ $notif->is_read ? 'text-muted' : 'fw-semibold' }}"
                               data-notif-id="{{ $notif->id }}"
                               onclick="markNotifRead({{ $notif->id }}, event)">
                                <div class="d-flex align-items-start gap-2">
                                    @if($notif->type === 'success')
                                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                    @elseif($notif->type === 'danger')
                                        <i class="bi bi-exclamation-circle-fill text-danger mt-1"></i>
                                    @else
                                        <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                                    @endif
                                    <div>
                                        <div class="small fw-semibold">{{ $notif->title }}</div>
                                        <div class="text-muted" style="font-size:0.78rem; white-space:normal;">{{ $notif->message }}</div>
                                        <div style="font-size:0.72rem;" class="text-secondary">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-3 text-center text-muted small">No notifications yet.</li>
                    @endforelse
                </ul>
            </div>

            <a href="javascript:void(0)"
               id="logoutBtn"
               class="btn btn-danger btn-sm mt-2 mt-lg-0">
                Logout
            </a>
        </div>
    </div>
</nav>

{{-- Scripts loaded BEFORE content so inline page scripts (flatpickr, $, Swal) can use them --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    @yield('content')
</div>

@if(session('success'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: '{{ session("success") }}',
    showConfirmButton: false,
    timer: 6000,
    timerProgressBar: true
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'error',
    title: '{{ session("error") }}',
    showConfirmButton: false,
    timer: 6000,
    timerProgressBar: true
});
</script>
@endif

<script>
$(document).on('submit', '.rentForm', function(e){
    e.preventDefault();
    let form = this;
    Swal.fire({
        title: 'Confirm Rental?',
        text: 'You will be redirected to Stripe payment.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Continue',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if(result.isConfirmed){
            form.submit();
        }
    });
});

$(document).ready(function(){
    $('#logoutBtn').click(function(){
        Swal.fire({
            title: 'Logout?',
            text: 'You will be signed out of your account.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Logout'
        }).then((result) => {
            if(result.isConfirmed){
                window.location.href = "{{ route('logout') }}";
            }
        });
    });
});
</script>

<script>
// ─── Notification helpers ───────────────────────────────────────────
function markNotifRead(id, e) {
    fetch('/notifications/' + id + '/mark-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    }).then(() => {
        const el = document.querySelector('[data-notif-id="' + id + '"]');
        if (el) {
            el.classList.remove('fw-semibold');
            el.classList.add('text-muted');
        }
        updateBadge();
    });
}

document.getElementById('markAllRead')?.addEventListener('click', function () {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    }).then(() => {
        document.querySelectorAll('#notifList .fw-semibold').forEach(el => {
            el.classList.remove('fw-semibold');
            el.classList.add('text-muted');
        });
        const badge = document.getElementById('notifBadge');
        if (badge) badge.remove();
        this.remove();
    });
});

function updateBadge() {
    fetch('/notifications/count')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('notifBadge');
            if (data.count === 0 && badge) {
                badge.remove();
            } else if (badge) {
                badge.textContent = data.count > 9 ? '9+' : data.count;
            }
        });
}
</script>

</body>
</html>