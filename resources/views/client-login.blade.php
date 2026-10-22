<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Log In</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            font-family: Arial, Helvetica, sans-serif;
            background: #102B21;
        }

        .main-card {
            width: 100%;
            max-width: 1149px;
            display: flex;
            overflow: hidden;
            border-radius: 30px;
            background: #D9D9D9;
        }

        .hero-panel {
            width: 52%;
            min-height: 839px;
            position: relative;
        }

        .hero-panel img {
            position: absolute;
            width: 100%;
            height: 100%;
            inset: 0;
            object-fit: cover;
        }

        .form-panel {
            flex: 1;
            min-width: 0;
            min-height: 839px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 40px 48px 28px;
            border-radius: 30px;
            background: #fff;
        }

        .form-content {
            width: 100%;
            max-width: 488px;
        }

        .logo {
            display: block;
            width: 274px;
            height: 144px;
            max-width: 100%;
            object-fit: contain;
            margin: 0 auto 24px;
        }

        .form-header {
            text-align: center;
            padding: 12px 0 24px;
        }

        .form-header h1 {
            margin: 0;
            color: #111827;
            font-size: 26px;
            line-height: 32px;
            letter-spacing: -.65px;
            font-weight: 700;
        }

        .form-header p {
            margin: 4px 0 0;
            color: #6B7280;
            font-size: 12px;
        }

        .google-button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: white;
            color: #4B5563;
            font-size: 13px;
            font-weight: 500;
        }

        .admin-box {
            width: 100%;
            margin-top: 16px;
            padding: 13px;
            border: 1px solid rgba(229,231,235,.8);
            border-radius: 12px;
            background: #D9D9D9;
        }

        .admin-heading {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #6B7280;
            font-size: 11px;
            font-weight: 600;
            line-height: 16.5px;
            letter-spacing: .55px;
            text-transform: uppercase;
        }

        .role-buttons {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 12px;
        }

        .role-button {
            width: 57px;
            height: 46px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: white;
            color: black;
            font-size: 11px;
            font-weight: 500;
            transition: .2s;
        }

        .role-button.active {
            background: #102B21;
            color: white;
        }

        .role-button.active svg {
            color: white;
        }

        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 0;
        }

        .divider-line {
            width: 96px;
            height: 1px;
            background: #E5E7EB;
        }

        .divider span {
            padding: 0 8px;
            color: #9CA3AF;
            font-size: 12px;
        }

        .account-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding-top: 4px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            outline: none;
            background: white;
            color: #111827;
            font-size: 13px;
        }

        .form-input::placeholder { color: #9CA3AF; }

        .form-input:focus {
            border-color: #102B21;
            box-shadow: 0 0 0 2px rgba(16,43,33,.1);
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap .form-input {
            padding-right: 40px;
        }

        .password-toggle {
            position: absolute;
            top: 11px;
            right: 12px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #9CA3AF;
            cursor: pointer;
        }

        .login-button {
            width: 100%;
            padding: 10px 16px;
            border: 0;
            border-radius: 50px;
            background: #102B21;
            color: white;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1);
            cursor: pointer;
        }

        .login-button:hover { background: #16402f; }

        .signup-text {
            padding-top: 16px;
            margin: 0;
            color: #6B7280;
            font-size: 12px;
            text-align: center;
        }

        .signup-text a {
            color: #FF383C;
            font-weight: 500;
            text-decoration: none;
        }

        .social-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding-top: 20px;
        }

        .social-links a {
            padding: 4px;
            color: #9CA3AF;
            text-decoration: none;
        }

        @media (max-width: 991px) {
            .main-card { max-width: 584px; }
            .hero-panel { display: none; }
            .form-panel { width: 100%; padding: 40px 32px 28px; }
        }

        @media (max-width: 575px) {
            .form-panel { min-height: auto; padding: 32px 24px; }
            .main-card { border-radius: 24px; }
            .form-panel { border-radius: 24px; }
        }
    </style>
</head>

<body>
<div class="main-card">

    <div class="hero-panel">
        <img
            src="https://api.builder.io/api/v1/image/assets/TEMP/bbe1f71b84adbf86b9cb159ffe42d386fe34f525?width=1192"
            alt="">
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
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M15.04 8.16667C15.04 7.64667 14.9933 7.14667 14.9067 6.66667H8V9.50667H11.9467C11.7733 10.42 11.2533 11.1933 10.4733 11.7133V13.56H12.8533C14.24 12.28 15.04 10.4 15.04 8.16667" fill="#4285F4"/>
                    <path d="M7.99979 15.3333C9.97979 15.3333 11.6398 14.68 12.8531 13.56L10.4731 11.7133C9.81979 12.1533 8.98646 12.42 7.99979 12.42C6.09313 12.42 4.47312 11.1333 3.89313 9.4H1.45312V11.2933C2.65979 13.6867 5.13313 15.3333 7.99979 15.3333" fill="#34A853"/>
                    <path d="M3.89268 9.39333C3.74602 8.95333 3.65935 8.48667 3.65935 8C3.65935 7.51333 3.74602 7.04667 3.89268 6.60667V4.70667H1.45268C.952682 5.7 .666016 6.81333 .666016 8C.666016 9.18667 .952682 10.3 1.45268 11.2933L3.35268 9.81333L3.89268 9.39333" fill="#FBBC05"/>
                    <path d="M7.99979 3.58667C9.07979 3.58667 10.0398 3.96 10.8065 4.68L12.9065 2.58C11.6331 1.39333 9.97979 .666668 7.99979 .666668C5.13313 .666668 2.65979 2.31333 1.45312 4.70667L3.89313 6.6C4.47312 4.86667 6.09313 3.58667 7.99979 3.58667" fill="#EA4335"/>
                </svg>
            </button>

            <div class="admin-box">
                <div class="admin-heading">
                    <i class="bi bi-shield-fill" style="color:#F25100"></i>
                    Admin portal access
                </div>

                <div class="role-buttons">
                    <button type="button" class="role-button active" id="clientRole">
                        <i class="bi bi-person" style="font-size:14px"></i>
                        Client
                    </button>

                    <button type="button" class="role-button" id="adminRole">
                        <i class="bi bi-shield-lock" style="font-size:14px"></i>
                        Admin
                    </button>
                </div>
            </div>

            <div class="divider">
                <div class="divider-line"></div>
                <span>or</span>
                <div class="divider-line"></div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert" style="margin-bottom: 12px; font-size: 12px;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
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

    const clientRole = document.getElementById('clientRole');
    const adminRole = document.getElementById('adminRole');

    clientRole.addEventListener('click', function () {
        clientRole.classList.add('active');
        adminRole.classList.remove('active');
    });

    adminRole.addEventListener('click', function () {
        adminRole.classList.add('active');
        clientRole.classList.remove('active');
    });
</script>
</body>
</html>
