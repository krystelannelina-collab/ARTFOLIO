@extends('layouts.auth')

@section('title', 'Log In')

@section('content')
    <header class="form-header">
        <h1>Log In Account</h1>
        <p>Join as a creative talent</p>
    </header>

    <div class="admin-box" role="note">
        <div class="admin-heading">
            <i class="bi bi-shield-fill" style="color:#F25100"></i>
            Admin access
        </div>
        <p class="mb-0 mt-2 text-secondary" style="font-size:12px">
            Administrators sign in here with their account and are taken to the admin dashboard.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger auth-alert" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-info auth-alert" role="status">
            {{ session('status') }}
        </div>
    @endif

    <form class="account-form" id="loginForm" action="{{ route('login') }}" method="POST">
        @csrf
        <input
            type="email"
            name="email"
            placeholder="Email Address"
            autocomplete="username"
            class="form-input"
            value="{{ old('email') }}"
            required
            autofocus>

        <div class="password-wrap">
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Password"
                autocomplete="current-password"
                class="form-input"
                required>

            <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>

        <div class="auth-row">
            <label class="terms-check">
                <input type="checkbox" name="remember" value="1">
                <span>Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="auth-link">Forgot your password?</a>
        </div>

        <button type="submit" class="login-button">
            Log In
        </button>
    </form>

    <p class="signup-text">
        Don’t have an account?
        <a href="{{ route('register') }}">Sign Up</a>
    </p>
@endsection
