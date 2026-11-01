<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Sign Up</title>
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
                src="https://api.builder.io/api/v1/image/assets/TEMP/fc8de5f172a6deead0a1ba38f30a585659cb87d6?width=549"
                alt="ARTFOLIO Logo"
                class="logo">

            <header class="form-header">
                <h1>Create Account</h1>
                <p>Join as a creative talent</p>
            </header>

            <button type="button" class="google-button">
                Sign up with Google
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M15.04 8.16667C15.04 7.64667 14.9933 7.14667 14.9067 6.66667H8V9.50667H11.9467C11.7733 10.42 11.2533 11.1933 10.4733 11.7133V13.56H12.8533C14.24 12.28 15.04 10.4 15.04 8.16667" fill="#4285F4"/>
                    <path d="M7.99979 15.3333C9.97979 15.3333 11.6398 14.68 12.8531 13.56L10.4731 11.7133C9.81979 12.1533 8.98646 12.42 7.99979 12.42C6.09313 12.42 4.47312 11.1333 3.89313 9.4H1.45312V11.2933C2.65979 13.6867 5.13313 15.3333 7.99979 15.3333" fill="#34A853"/>
                    <path d="M3.89366 9.39333C3.74699 8.95333 3.66033 8.48667 3.66033 8C3.66033 7.51333 3.74699 7.04667 3.89366 6.60667V4.70667H1.45366C.953659 5.7 .666992 6.81333 .666992 8C.666992 9.18667 .953659 10.3 1.45366 11.2933L3.35366 9.81333L3.89366 9.39333" fill="#FBBC05"/>
                    <path d="M7.99979 3.58667C9.07979 3.58667 10.0398 3.96 10.8065 4.68L12.9065 2.58C11.6331 1.39333 9.97979 .666668 7.99979 .666668C5.13313 .666668 2.65979 2.31333 1.45312 4.70667L3.89313 6.6C4.47312 4.86667 6.09313 3.58667 7.99979 3.58667" fill="#EA4335"/>
                </svg>
            </button>

            <div class="role-box" role="note">
                <div class="role-heading">
                    <i class="bi bi-shield-fill" style="color:#F25100"></i>
                    <span>Client Account Registration</span>
                </div>
                <p class="mb-0 mt-2 text-secondary" style="font-size:12px">
                    Public registration creates a client account. Administrator access must be granted by a system administrator.
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

            @if (session('status'))
                <div class="alert alert-info auth-alert" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form class="account-form" id="signupForm" action="{{ url('/register') }}" method="POST">
                @csrf
                <input
                    type="text"
                    name="name"
                    placeholder="Full Name"
                    class="form-input"
                    autocomplete="name"
                    value="{{ old('name') }}"
                    required>
                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    class="form-input"
                    autocomplete="email"
                    value="{{ old('email') }}"
                    required>

                <div class="password-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password (min. 8 characters)"
                        class="form-input"
                        autocomplete="new-password"
                        minlength="8"
                        required>
                    <button type="button" class="password-toggle" id="passwordToggle" aria-label="Show password">
                        <svg id="eyeOffIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M3 3l18 18M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
                            <path d="M9.9 5.2A10.9 10.9 0 0 1 12 5c5 0 8.5 4.5 9 7-.2 1-1.2 2.7-2.8 4M6.2 6.2C3.8 7.8 2.2 10 2 12c.5 2.5 4 7 10 7 1.4 0 2.7-.3 3.8-.8"></path>
                        </svg>
                        <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                <button type="submit" class="create-button" id="submitButton">Create Account</button>
            </form>

            <p class="login-text">
                Already have an account?
                <a href="{{ url('/login') }}">Log in</a>
            </p>

            <div class="social-links">
                <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </section>
</div>

<script>
    const password = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');

    passwordToggle.addEventListener('click', function () {
        const isPassword = password.type === 'password';
        password.type = isPassword ? 'text' : 'password';
        eyeIcon.style.display = isPassword ? 'block' : 'none';
        eyeOffIcon.style.display = isPassword ? 'none' : 'block';
    });
</script>
</body>
</html>
