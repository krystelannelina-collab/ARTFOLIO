@extends('layouts.auth')

@section('title', 'Log In')

@section('content')
    @php($selectedPortal = old('login_as', 'client') === 'admin' ? 'admin' : 'client')

    <header class="form-header">
        <h1>Log In Account</h1>
        <p id="portalSubtitle">
            {{ $selectedPortal === 'admin' ? 'Sign in to manage the ARTFOLIO store' : 'Join as a creative talent' }}
        </p>
    </header>

    <fieldset class="portal-picker">
        <legend class="role-heading">Log in as</legend>

        <div class="portal-options">
            <label class="portal-option">
                <input type="radio" name="login_as" value="client" form="loginForm"
                       data-subtitle="Join as a creative talent"
                       @checked($selectedPortal === 'client')>
                <span class="portal-card">
                    <i class="bi bi-person-fill" aria-hidden="true"></i>
                    <span class="portal-title">Client</span>
                    <span class="portal-hint">Browse and buy artworks</span>
                </span>
            </label>

            <label class="portal-option">
                <input type="radio" name="login_as" value="admin" form="loginForm"
                       data-subtitle="Sign in to manage the ARTFOLIO store"
                       @checked($selectedPortal === 'admin')>
                <span class="portal-card">
                    <i class="bi bi-shield-fill" aria-hidden="true"></i>
                    <span class="portal-title">Admin</span>
                    <span class="portal-hint">Manage the store dashboard</span>
                </span>
            </label>
        </div>
    </fieldset>

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

    <p class="signup-text" id="signupPrompt" @if ($selectedPortal === 'admin') hidden @endif>
        Don’t have an account?
        <a href="{{ route('register') }}">Sign Up</a>
    </p>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="login_as"]').forEach((option) => {
        option.addEventListener('change', () => {
            document.getElementById('portalSubtitle').textContent = option.dataset.subtitle;
            document.getElementById('signupPrompt').hidden = option.value === 'admin';
        });
    });
</script>
@endpush
