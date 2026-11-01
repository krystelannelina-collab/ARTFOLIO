<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Log In</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
<div class="main-card">

    <div class="hero-panel">
        <img
            src="https://api.builder.io/api/v1/image/assets/TEMP/bbe1f71b84adbf86b9cb159ffe42d386fe34f525?width=1192"
            alt=""
            onerror="this.src='{{ asset('images/hero-banner.png') }}';">
    </div>

    <section class="form-panel">
        <div class="form-content">

            <img
                src="https://api.builder.io/api/v1/image/assets/TEMP/c33f41d46e0fc49a2584e3b9831f75f33bfbd374?width=549"
                alt="ARTFOLIO Logo"
                class="logo">

            <header class="form-header">
                <h1>Log In Account</h1>
                <p>Join as a creative talent</p>
            </header>

            <button type="button" class="google-button">
                Login with Google
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M15.04 8.16667C15.04 7.64667 14.9933 7.14667 14.9067 6.66667H8V9.50667H11.9467C11.7733 10.42 11.2533 11.1933 10.4733 11.7133V13.56H12.8533C14.24 12.28 15.04 10.4 15.04 8.16667" fill="#4285F4"/>
                    <path d="M7.99979 15.3333C9.97979 15.3333 11.6398 14.68 12.8531 13.56L10.4731 11.7133C9.81979 12.1533 8.98646 12.42 7.99979 12.42C6.09313 12.42 4.47312 11.1333 3.89313 9.4H1.45312V11.2933C2.65979 13.6867 5.13313 15.3333 7.99979 15.3333" fill="#34A853"/>
                    <path d="M3.89268 9.39333C3.74602 8.95333 3.65935 8.48667 3.65935 8C3.65935 7.51333 3.74602 7.04667 3.89268 6.60667V4.70667H1.45268C.952682 5.7 .666016 6.81333 .666016 8C.666016 9.18667 .952682 10.3 1.45268 11.2933L3.35268 9.81333L3.89268 9.39333" fill="#FBBC05"/>
                    <path d="M7.99979 3.58667C9.07979 3.58667 10.0398 3.96 10.8065 4.68L12.9065 2.58C11.6331 1.39333 9.97979 .666668 7.99979 .666668C5.13313 .666668 2.65979 2.31333 1.45312 4.70667L3.89313 6.6C4.47312 4.86667 6.09313 3.58667 7.99979 3.58667" fill="#EA4335"/>
                </svg>
            </button>

            <div class="admin-box" role="note">
                <div class="admin-heading">
                    <i class="bi bi-shield-fill" style="color:#F25100"></i>
                    Admin access
                </div>
                <p class="mb-0 mt-2 text-secondary" style="font-size:12px">
                    Administrators sign in here with their account and are taken to the admin dashboard.
                </p>
            </div>

            <div class="divider">
                <div class="divider-line"></div>
                <span>or</span>
                <div class="divider-line"></div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger auth-alert" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success auth-alert" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-info auth-alert" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form class="account-form" id="loginForm" action="{{ url('/login') }}" method="POST">
                @csrf
                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    autocomplete="email"
                    class="form-input"
                    value="{{ old('email') }}"
                    required>

                <div class="password-wrap">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Password"
                        autocomplete="current-password"
                        class="form-input"
                        required>

                    <button
                        type="button"
                        id="togglePassword"
                        aria-label="Toggle password visibility"
                        class="password-toggle">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </button>
                </div>

                <button type="submit" class="login-button">
                    Log In
                </button>
            </form>

            <p class="signup-text">
                Don’t have an account?
                <a href="{{ url('/client-signup') }}">Sign Up</a>
            </p>
        </div>

        <div class="social-links">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
        </div>
    </section>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const show = passwordInput.type === 'password';
        passwordInput.type = show ? 'text' : 'password';
        eyeIcon.className = show ? 'bi bi-eye' : 'bi bi-eye-slash';
    });
</script>
</body>
</html>
