<!doctype html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body>
    <div class="app-shell">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="sidebar-backdrop" data-sidebar-close></div>
        <main class="main-content">
            @yield('content')
        </main>
    </div>
    @stack('modals')
    @include('partials.scripts')
</body>
</html>
