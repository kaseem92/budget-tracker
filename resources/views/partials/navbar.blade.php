<nav class="topbar">
    <div class="topbar-brand">
        <button class="btn sidebar-toggle" type="button" aria-label="Toggle navigation"><i class="bi bi-list"></i></button>
        <a href="{{ route('dashboard') }}" class="brand-text"><strong>Budget Tracker</strong><small>Personal Finance Portal</small></a>
    </div>
    <div class="topbar-content">
        <div class="d-none d-md-block"><span class="text-muted small">@yield('title', 'Dashboard')</span></div>
        <div class="dropdown ms-auto">
            <button class="user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="user-avatar">B</span>
                <span class="d-none d-sm-block text-start"><strong>Budget User</strong><small>UI Preview</small></span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-sm">
                <a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            </div>
        </div>
    </div>
</nav>
