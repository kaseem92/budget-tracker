<nav class="topbar">
    <div class="topbar-brand">
        <button class="btn sidebar-toggle" type="button" aria-label="Toggle navigation"><i class="bi bi-list"></i></button>
        <a href="{{ route('dashboard') }}" class="brand-text"><strong>Budget Tracker</strong><small>Personal Finance
                Portal</small></a>
    </div>
    <div class="topbar-content">
        <div class="d-none d-md-block"><span class="text-muted small">@yield('title', 'Dashboard')</span></div>
        <div class="dropdown ms-auto">
            <button class="user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="d-none d-sm-block text-start"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small></span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-sm">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item" type="submit"><i
                            class="bi bi-box-arrow-right me-2"></i>Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>
