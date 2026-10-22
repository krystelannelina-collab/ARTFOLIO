<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Sign Up - ARTFOLIO</title>
    <meta name="description" content="Create your ARTFOLIO account">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }
body {
    min-height: 100vh;
    margin: 0;
    background: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
        

        .signup-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 7%;
        }

        .signup-card {
            display: flex;
            width: 100%;
            max-width: 1149px;
            min-height: 839px;
            overflow: hidden;
            border-radius: 30px;
            background: #D9D9D9;
        }

        .hero-panel {
            position: relative;
            flex: 1;
            min-width: 0;
        }

        .hero-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-panel {
            display: flex;
            width: 584px;
            flex-shrink: 0;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 48px;
            border-radius: 30px;
            background: #fff;
        }

        .form-content {
            width: 100%;
            max-width: 488px;
        }

        .brand-logo {
            display: block;
            width: 274px;
            max-width: 100%;
            height: 144px;
            margin: 0 auto;
            object-fit: contain;
        }

        .page-title {
            margin-top: 8px;
            color: #111827;
            font-size: 26px;
            font-weight: 700;
            line-height: 32px;
            letter-spacing: -.65px;
            text-align: center;
        }

        .page-subtitle {
            margin-top: 4px;
            color: #6B7280;
            font-size: 12px;
            text-align: center;
        }

        .google-button {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            padding: 10px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: white;
            color: #4B5563;
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }

        .google-button:hover {
            background: #F9FAFB;
        }

        .role-box {
            margin-top: 16px;
            padding: 12px;
            border: 1px solid rgba(229,231,235,.8);
            border-radius: 12px;
            background: #D9D9D9;
        }

        .role-label {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #6B7280;
            font-size: 11px;
            font-weight: 600;
            line-height: 16px;
            letter-spacing: .55px;
            text-transform: uppercase;
        }

        .role-options {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 8px;
        }

        .role-button {
            display: flex;
            width: 57px;
            height: 46px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: #fff;
            color: #374151;
            font-size: 11px;
            font-weight: 500;
        }

        .role-button.active {
            background: #102B21;
            color: #fff;
        }

        .role-button svg {
            width: 15px;
            height: 15px;
        }

        .divider-row {
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

        .divider-text {
            padding: 0 8px;
            color: #9CA3AF;
            font-size: 12px;
        }

        .signup-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            outline: none;
            background: #fff;
            color: #111827;
            font-size: 13px;
        }

        .form-input::placeholder {
            color: #9CA3AF;
        }

        .form-input:focus {
            border-color: #102B21;
            box-shadow: 0 0 0 3px rgba(16,43,33,.10);
        }

        .password-wrapper {
            position: relative;
            padding-bottom: 8px;
        }

        .password-wrapper .form-input {
            padding-right: 42px;
        }

        .password-toggle {
            position: absolute;
            top: 10px;
            right: 12px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #9CA3AF;
        }

        .password-toggle svg {
            width: 16px;
            height: 16px;
        }

        .submit-button {
            width: 100%;
            padding: 10px 16px;
            border: none;
            border-radius: 50px;
            background: #102B21;
            color: white;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 3px 6px rgba(0,0,0,.12);
        }

        .submit-button:hover {
            background: #1C4434;
        }

        .login-text {
            margin-top: 16px;
            color: #6B7280;
            font-size: 12px;
            text-align: center;
        }

        .login-text .dot {
            padding: 0 8px;
            color: #D1D5DB;
        }

        .login-link {
            color: #FF383C;
            font-weight: 500;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .social-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9CA3AF;
        }

        .social-links svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
        }

        @media (max-width: 991.98px) {
            .signup-wrapper {
                padding: 32px 16px;
            }

            .signup-card {
                max-width: 584px;
                min-height: auto;
                background: white;
            }

            .hero-panel {
                display: none;
            }

            .form-panel {
                width: 100%;
                padding: 40px 32px;
            }
        }

        @media (max-width: 575.98px) {
            .signup-wrapper {
                padding: 16px 10px;
            }

            .signup-card {
                border-radius: 22px;
            }

            .form-panel {
                padding: 28px 20px;
                border-radius: 22px;
            }

            .brand-logo {
                height: 120px;
            }
        }
    </style>
</head>

<body>
<div class="signup-wrapper">
    <div class="signup-card">

        <div class="hero-panel">
            <img
                src="https://api.builder.io/api/v1/image/assets/TEMP/bbe1f71b84adbf86b9cb159ffe42d386fe34f525?width=1192"
                alt=""
                class="hero-image"
            >
        </div>

        <div class="form-panel">
            <div class="form-content">

                <div class="text-center">
                    <img
                        src="https://api.builder.io/api/v1/image/assets/TEMP/fc8de5f172a6deead0a1ba38f30a585659cb87d6?width=549"
                        alt="ARTFOLIO Logo"
                        class="brand-logo"
                    >

                    <h1 class="page-title" id="pageTitle">Admin Sign Up</h1>
                    <p class="page-subtitle" id="pageSubtitle">
                        Join as a creative talent
                    </p>
                </div>

                <button type="button" class="google-button" id="googleButton">
                    Sign up with Google
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M15.04 8.16667C15.04 7.64667 14.9933 7.14667 14.9067 6.66667H8V9.50667H11.9467C11.7733 10.42 11.2533 11.1933 10.4733 11.7133V13.56H12.8533C14.24 12.28 15.04 10.4 15.04 8.16667" fill="#4285F4"/>
                        <path d="M7.99979 15.3333C9.97979 15.3333 11.6398 14.68 12.8531 13.56L10.4731 11.7133C9.81979 12.1533 8.98646 12.42 7.99979 12.42C6.09313 12.42 4.47312 11.1333 3.89313 9.4H1.45312V11.2933C2.65979 13.6867 5.13313 15.3333 7.99979 15.3333" fill="#34A853"/>
                        <path d="M3.89366 9.39333C3.74699 8.95333 3.66033 8.48667 3.66033 8C3.66033 7.51333 3.74699 7.04667 3.89366 6.60667V4.70667H1.45366C.953659 5.7 .666992 6.81333 .666992 8C.666992 9.18667 .953659 10.3 1.45366 11.2933L3.35366 9.81333L3.89366 9.39333" fill="#FBBC05"/>
                        <path d="M7.99979 3.58667C9.07979 3.58667 10.0398 3.96 10.8065 4.68L12.9065 2.58C11.6331 1.39333 9.97979 .666666 7.99979 .666666C5.13313 .666666 2.65979 2.31333 1.45312 4.70667L3.89313 6.6C4.47312 4.86667 6.09313 3.58667 7.99979 3.58667" fill="#EA4335"/>
                    </svg>
                </button>

                <div class="role-box">
                    <div class="role-label">
                        <svg width="12" height="12" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M8 1.3 13 3.2V7.1C13 10.4 10.8 13 8 14.5 5.2 13 3 10.4 3 7.1V3.2L8 1.3Z" stroke="#F25100" stroke-width="1.7"/>
                            <path d="m5.7 7.7 1.5 1.5 3.2-3.4" stroke="#F25100" stroke-width="1.7"/>
                        </svg>
                        <span>Admin portal access</span>
                    </div>

                    <div class="role-options">
                        <button type="button" class="role-button" id="clientRole" data-role="client">
                            <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                <circle cx="8" cy="4.5" r="3"/>
                                <path d="M2 14c0-3.1 2.5-5 6-5s6 1.9 6 5v1H2z"/>
                            </svg>
                            Client
                        </button>

                        <button type="button" class="role-button active" id="adminRole" data-role="admin">
                            <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                <path d="M8 1.3 13 3.2V7.1C13 10.4 10.8 13 8 14.5 5.2 13 3 10.4 3 7.1V3.2L8 1.3Z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="m5.7 7.7 1.5 1.5 3.2-3.4" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            Admin
                        </button>
                    </div>
                </div>

                <div class="divider-row">
                    <div class="divider-line"></div>
                    <span class="divider-text">or</span>
                    <div class="divider-line"></div>
                </div>

                <form class="signup-form" id="signupForm" action="{{ url('/register') }}" method="POST">
                    @csrf
                    <input
                        type="text"
                        name="name"
                        placeholder="Full Name"
                        class="form-input"
                        autocomplete="name"
                        required
                    >

                    <input
                        type="email"
                        name="email"
                        placeholder="Email Address"
                        class="form-input"
                        autocomplete="email"
                        required
                    >

                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Password"
                            class="form-input"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="passwordToggle"
                            aria-label="Show password"
                        >
                            <svg id="eyeOffIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 3l18 18M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                <path d="M9.9 5.2A10.9 10.9 0 0 1 12 5c5 0 8.5 4.5 9 7-.2 1-1.2 2.7-2.8 4M6.2 6.2C3.8 7.8 2.2 10 2 12c.5 2.5 4 7 10 7 1.4 0 2.7-.3 3.8-.8"/>
                            </svg>
                            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>

                    <input type="hidden" name="role" id="selectedRole" value="admin">

                    <button type="submit" class="submit-button" id="submitButton">
                        Create Account
                    </button>
                </form>

                <p class="login-text">
                    Already have an account?
                    <span class="dot">•</span>
                    <a href="{{ url('/login') }}" class="login-link">Log in</a>
                </p>

                <div class="social-links">
                    <a href="#" aria-label="Facebook">
                        <svg viewBox="0 0 14 14">
                            <path d="M14 7.04A7 7 0 1 0 5.91 13.96V9.07H4.13V7.04h1.78V5.5c0-1.75 1.05-2.72 2.64-2.72.77 0 1.57.14 1.57.14v1.72h-.88c-.87 0-1.14.54-1.14 1.09v1.31h1.94l-.31 2.02H8.1v4.89A7 7 0 0 0 14 7.04Z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="X">
                        <svg viewBox="0 0 14 14">
                            <path d="M10.6 1.3h1.9L8.36 6.13l4.96 6.56H9.43L6.39 8.71l-3.48 3.98H.98l4.51-5.16L.73 1.31h3.98l2.75 3.64 3.14-3.65Zm-.67 10.22H11L4.13 2.41H2.98l6.95 9.11Z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <svg viewBox="0 0 14 14">
                            <path d="M11.08 0H2.92A2.92 2.92 0 0 0 0 2.92v8.16A2.92 2.92 0 0 0 2.92 14h8.16A2.92 2.92 0 0 0 14 11.08V2.92A2.92 2.92 0 0 0 11.08 0ZM4.67 11.08H2.92V4.67h1.75v6.41ZM3.79 3.93a1.03 1.03 0 1 1 0-2.06 1.03 1.03 0 0 1 0 2.06Zm7.88 7.15H9.92V7.81c0-1.96-2.34-1.81-2.34 0v3.27H5.83V4.67h1.75V5.7c.82-1.51 4.09-1.62 4.09 1.44v3.94Z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <svg viewBox="0 0 14 14">
                            <path d="M7 1.26c1.87 0 2.09.01 2.83.04 1.9.09 2.79.99 2.87 2.87.04.74.04.96.04 2.83s-.01 2.09-.04 2.83c-.09 1.88-.97 2.79-2.87 2.87-.74.04-.96.04-2.83.04s-2.09-.01-2.83-.04c-1.9-.09-2.78-.99-2.87-2.87-.03-.74-.04-.96-.04-2.83s.01-2.09.04-2.83c.09-1.88.97-2.79 2.87-2.87.74-.03.96-.04 2.83-.04ZM7 0C5.1 0 4.86.01 4.11.04 1.57.16.16 1.57.04 4.11.01 4.86 0 5.1 0 7s.01 2.14.04 2.89c.12 2.54 1.53 3.95 4.07 4.07.75.03.99.04 2.89.04s2.14-.01 2.89-.04c2.54-.12 3.95-1.53 4.07-4.07.03-.75.04-.99.04-2.89s-.01-2.14-.04-2.89C13.84 1.57 12.43.16 9.89.04 9.14.01 8.9 0 7 0Zm0 3.41a3.59 3.59 0 1 0 0 7.18 3.59 3.59 0 0 0 0-7.18Zm0 5.92A2.33 2.33 0 1 1 7 4.67a2.33 2.33 0 0 1 0 4.66Zm3.74-6.91a.84.84 0 1 0 0 1.68.84.84 0 0 0 0-1.68Z"/>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    const clientRole = document.getElementById('clientRole');
    const adminRole = document.getElementById('adminRole');
    const selectedRole = document.getElementById('selectedRole');
    const pageTitle = document.getElementById('pageTitle');
    const submitButton = document.getElementById('submitButton');
    const password = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');
    const signupForm = document.getElementById('signupForm');

    function selectRole(role) {
        selectedRole.value = role;

        clientRole.classList.toggle('active', role === 'client');
        adminRole.classList.toggle('active', role === 'admin');

        pageTitle.textContent = 'Create Account';
        submitButton.textContent = 'Create Account';
    }

    clientRole.addEventListener('click', function () {
        selectRole('client');
    });

    adminRole.addEventListener('click', function () {
        selectRole('admin');
    });

    passwordToggle.addEventListener('click', function () {
        const isPassword = password.type === 'password';

        password.type = isPassword ? 'text' : 'password';
        eyeIcon.style.display = isPassword ? 'block' : 'none';
        eyeOffIcon.style.display = isPassword ? 'none' : 'block';
        passwordToggle.setAttribute(
            'aria-label',
            isPassword ? 'Hide password' : 'Show password'
        );
    });

    selectRole('admin');
</script>

</body>
</html>