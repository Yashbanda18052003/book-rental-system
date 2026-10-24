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

<div class="admin-wrapper">

<!-- Sidebar -->
<div class="admin-sidebar" id="sidebar">

    <div class="sidebar-logo text-center">
        <img src="{{ asset('images/booknest-logo.png') }}"
             alt="BookNest"
             class="sidebar-logo-img">
        <h4 class="sidebar-logo-text">BookNest</h4>
    </div>

    <ul>
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                <span>Books</span>
            </a>
        </li>
        <li>
            <a href="{{ route('my.rentals') }}" class="{{ request()->routeIs('my.rentals') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i>
                <span>My Rentals</span>
            </a>
        </li>
        <li>
            <a href="{{ route('my.reservations') }}" class="{{ request()->routeIs('my.reservations') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>My Reservations</span>
            </a>
        </li>
        <li>
            <a href="{{ route('membership.plans') }}" class="{{ request()->routeIs('membership.plans') ? 'active' : '' }}">
                <i class="bi bi-award"></i>
                <span>Membership Plans</span>
            </a>
        </li>
        <li>
            <a href="{{ route('my.subscription') }}" class="{{ request()->routeIs('my.subscription') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i>
                <span>My Subscription</span>
            </a>
        </li>
        <li>
            <a href="{{ route('my.fines') }}" class="{{ request()->routeIs('my.fines') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i>
                <span>My Fines</span>
            </a>
        </li>
        <li>
            <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="bi bi-person-fill"></i>
                <span>My Profile</span>
            </a>
        </li>
    </ul>

</div>

<!-- Content -->
<div class="admin-content" id="content">

    <div class="admin-topbar d-flex justify-content-between align-items-center">

        <button class="btn btn-outline-light" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>

        <div class="d-flex gap-2 align-items-center">

            @php
                $unreadCount   = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                $notifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(10)->get();
            @endphp

            {{-- Notification Bell --}}
            <div class="dropdown me-1">
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

            {{-- User Profile Dropdown --}}
            <div class="dropdown ms-1">
                <button class="btn profile-dropdown-btn"
                        type="button"
                        id="userProfileDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <div class="user-avatar-wrapper">
                        @if(auth()->user()->profile_picture_url)
                            <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}">
                        @else
                            <span class="user-avatar-initial">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <span class="d-none d-md-inline fw-semibold text-white ms-1">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down text-secondary small ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow user-dropdown-menu" aria-labelledby="userProfileDropdown">
                    <li class="user-dropdown-header">
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-wrapper lg">
                                @if(auth()->user()->profile_picture_url)
                                    <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}">
                                @else
                                    <span class="user-avatar-initial lg">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="overflow-hidden">
                                <div class="fw-bold text-white text-truncate" style="max-width: 170px;">{{ auth()->user()->name }}</div>
                                <div class="text-secondary small text-truncate" style="max-width: 170px;">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('profile') }}" class="user-dropdown-item">
                            <i class="bi bi-person-circle text-warning"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('my.rentals') }}" class="user-dropdown-item">
                            <i class="bi bi-journal-check text-primary"></i>
                            <span>My Rentals</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('my.subscription') }}" class="user-dropdown-item">
                            <i class="bi bi-credit-card text-info"></i>
                            <span>My Subscription</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1 border-secondary" style="opacity: 0.2;"></li>
                    <li>
                        <a href="javascript:void(0)" class="user-dropdown-item danger-item triggerLogout">
                            <i class="bi bi-box-arrow-right text-danger"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

    </div>

    {{-- Scripts loaded BEFORE content so inline page scripts (flatpickr, $, Swal) can use them --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="main-content-body container-fluid">
        @yield('content')
    </div>

</div>

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
    $('#toggleSidebar').click(function(){
        if(window.innerWidth <= 991){
            $('#sidebar').toggleClass('active');
        } else {
            $('#sidebar').toggleClass('collapsed');
            $('#content').toggleClass('expanded');
        }
    });

    $(document).on('click', '#logoutBtn, .triggerLogout', function(){
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