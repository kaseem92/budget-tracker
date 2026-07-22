<!doctype html>
<html lang="en">
<head>
    @include('partials.head')
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>
<body class="auth-page">
    <main class="auth-layout">@yield('content')</main>
    <footer class="auth-footer"><span>Personal Finance Portal</span><span>Budget Tracker</span></footer>
    @include('partials.scripts')
</body>
</html>
