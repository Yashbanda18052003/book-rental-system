<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookNest Admin</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
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
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.index') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                <span>Books</span>
            </a>
        </li>
        <li>
            <a href="{{ route('books.create') }}" class="{{ request()->routeIs('books.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>Add Book</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.rentals') }}" class="{{ request()->routeIs('admin.rentals') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i>
                <span>Rentals</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.reservations') }}" class="{{ request()->routeIs('admin.reservations') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>Reservations</span>
            </a>
        </li>
        <li>
            <a href="{{ route('plans.index') }}" class="{{ request()->routeIs('plans.index') ? 'active' : '' }}">
                <i class="bi bi-award"></i>
                <span>Membership Plans</span>
            </a>
        </li>
        <li>
            <a href="{{ route('plans.create') }}" class="{{ request()->routeIs('plans.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>Add Membership Plan</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.subscriptions') }}" class="{{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i>
                <span>Subscriptions</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.fines') }}" class="{{ request()->routeIs('admin.fines') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i>
                <span>Fines</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.payments') }}" class="{{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                <span>Payments</span>
            </a>
        </li>
        <li>
            <a href="{{ route('reports') }}" class="{{ request()->routeIs('reports') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i>
                <span>Reports</span>
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
                $adminUnreadCount   = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                $adminNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(10)->get();
            @endphp

            {{-- Notification Bell --}}
            <div class="dropdown">
                <button class="btn btn-outline-light position-relative"
                        type="button"
                        id="adminNotifDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="bi bi-bell-fill"></i>
                    @if($adminUnreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="adminNotifBadge">
                            {{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow"
                    style="min-width:340px; max-height:420px; overflow-y:auto;"
                    id="adminNotifList">
                    <li class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom">
                        <strong>Notifications</strong>
                        @if($adminUnreadCount > 0)
                            <a href="javascript:void(0)" id="adminMarkAllRead" class="text-primary small">Mark all as read</a>
                        @endif
                    </li>
                    @forelse($adminNotifications as $notif)
                        <li>
                            <a href="{{ $notif->url ?? '#' }}"
                               class="dropdown-item py-2 {{ $notif->is_read ? 'text-muted' : 'fw-semibold' }}"
                               data-notif-id="{{ $notif->id }}"
                               onclick="markAdminNotifRead({{ $notif->id }}, event)">
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

            {{-- Admin Profile Dropdown --}}
            <div class="dropdown ms-1">
                <button class="btn profile-dropdown-btn"
                        type="button"
                        id="adminProfileDropdown"
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
                    <span class="badge bg-warning text-dark ms-1 d-none d-md-inline-block" style="font-size:0.68rem; padding: 2px 6px;">Admin</span>
                    <i class="bi bi-chevron-down text-secondary small ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow user-dropdown-menu" aria-labelledby="adminProfileDropdown">
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
                        <a href="{{ route('admin.profile') }}" class="user-dropdown-item">
                            <i class="bi bi-person-circle text-warning"></i>
                            <span>Admin Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings') }}" class="user-dropdown-item">
                            <i class="bi bi-gear-fill text-info"></i>
                            <span>System Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.activity.logs') }}" class="user-dropdown-item">
                            <i class="bi bi-clock-history text-success"></i>
                            <span>Activity Logs</span>
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

    {{-- Scripts loaded BEFORE content so inline page scripts (DataTables, Chart.js, $, Swal) can use them --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed){
                window.location.href = "{{ route('logout') }}";
            }
        });
    });

});
</script>

@stack('scripts')

<script>
// ─── Admin Notification helpers ─────────────────────────────────────
function markAdminNotifRead(id, e) {
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
        updateAdminBadge();
    });
}

document.getElementById('adminMarkAllRead')?.addEventListener('click', function () {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    }).then(() => {
        document.querySelectorAll('#adminNotifList .fw-semibold').forEach(el => {
            el.classList.remove('fw-semibold');
            el.classList.add('text-muted');
        });
        const badge = document.getElementById('adminNotifBadge');
        if (badge) badge.remove();
        this.remove();
    });
});

function updateAdminBadge() {
    fetch('/notifications/count')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('adminNotifBadge');
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
