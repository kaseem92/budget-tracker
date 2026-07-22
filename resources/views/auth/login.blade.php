@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <section class="login-card">
    <div class="login-brand"><span class="brand-icon"><i class="bi bi-wallet2"></i></span>
        <div><strong>Budget Tracker</strong>
            <small>Personal Finance Portal</small>
        </div>
    </div>
    <div class="login-heading">
        <h1>Welcome back</h1>
        <p>Sign in to manage your monthly budget and daily expenses.</p>
    </div>

        <div class="login-alert-wrap" aria-live="assertive">
    @error('email')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    @enderror
        </div>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">Email address</label>
            <div class="input-icon"><i class="bi bi-envelope"></i>
                <input class="form-control" id="email" type="email" name="email" placeholder="name@example.com" autocomplete="username" value="{{ old('email') }}" required autofocus>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <div class="input-icon password-wrap"><i class="bi bi-lock"></i>
                <input class="form-control" id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                <button type="button" class="password-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
            </div>
        </div>
        <label class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember">
            <span class="form-check-label">Remember me</span>
        </label>
        <button class="btn btn-warning w-100 login-button" type="submit">
        <i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
    </form>
    <div class="login-features">
        <span><i class="bi bi-calendar-check"></i>Track Monthly Budget</span>
        <span><i class="bi bi-receipt-cutoff"></i>Manage Daily Expenses</span>
        <span><i class="bi bi-pie-chart"></i>Visual Spending Reports</span>
    </div>
</section>
@endsection
