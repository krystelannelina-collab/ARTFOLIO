<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            inset: 0;
            width: 100%;
            height: 100%;
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
            max-width: 100%;
            height: auto;
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
            background: #fff;
            color: #4B5563;
            font-size: 13px;
            font-weight: 500;
        }

        .role-box {
            width: 100%;
            margin-top: 16px;
            padding: 13px;
            border: 1px solid rgba(229,231,235,.8);
            border-radius: 12px;
            background: #D9D9D9;
        }

        .role-heading {
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
            margin-top: 8px;
        }

        .role-button {
            min-width: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 6px 13.5px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: #fff;
            color: #374151;
            font-size: 11px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
        }

        .role-button.active {
            border-color: #102B21;
            outline: 1px solid #102B21;
            outline-offset: -1px;
        }

        .divider {
            width: 100%;
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
            line-height: 16px;
        }

        .account-form {
            width: 100%;
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
            background: #fff;
            color: #111827;
            font-size: 13px;
            line-height: 18px;
        }

        .form-input::placeholder { color: #9CA3AF; }
        .form-input:focus {
            border-color: #102B21;
            box-shadow: 0 0 0 2px rgba(16,43,33,.1);
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap .form-input { padding-right: 40px; }

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

        .create-button {
            width: 100%;
            padding: 10px 16px;
            border: 0;
            border-radius: 50px;
            background: #102B21;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1);
            cursor: pointer;
        }

        .login-text {
            padding-top: 16px;
            margin: 0;
            color: #6B7280;
            font-size: 12px;
            text-align: center;
        }

        .login-text a {
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
            color: #9CA3AF;
            text-decoration: none;
        }

        @media (max-width: 991px) {
            .main-card { max-width: 584px; }
            .hero-panel { display: none; }
            .form-panel { width: 100%; padding: 40px 32px 28px; }
        }

        @media (max-width: 575px) {
            .main-card, .form-panel { border-radius: 24px; }
            .form-panel { min-height: auto; padding: 32px 24px; }
        }
    </style>
</head>
<body>
<div class="main-card">
    <div class="hero-panel">
        <img src="https://api.builder.io/api/v1/image/assets/TEMP/bbe1f71b84adbf86b9cb159ffe42d386fe34f525?width=1192" alt="">
    </div>

    <section class="form-panel">
        <div class="form-content">
            <img src="https://api.builder.io/api/v1/image/assets/TEMP/fc8de5f172a6deead0a1ba38f30a585659cb87d6?width=549" alt="Logo" class="logo">

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

            <div class="role-box">
                <div class="role-heading">
                    <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.66667 11.6667C3.31528 11.3264 2.19965 10.551 1.31979 9.34062C0.439931 8.13021 0 6.78611 0 5.30833V1.75L4.66667 0L9.33333 1.75V5.30833C9.33333 6.78611 8.8934 8.13021 8.01354 9.34062C7.13368 10.551 6.01806 11.3264 4.66667 11.6667Z" fill="#F25100"/>
                    </svg>
                    <span>Admin Portal Access</span>
                </div>

                <div class="role-buttons">
                    <button type="button" class="role-button" id="clientRole" data-role="client">
                        <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                            <circle cx="8" cy="4.5" r="3"></circle>
                            <path d="M2 14c0-3.1 2.5-5 6-5s6 1.9 6 5v1H2z"></path>
                        </svg>
                        Client
                    </button>

                    <button type="button" class="role-button active" id="adminRole" data-role="admin">
                        <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M8 1.3 13 3.2V7.1C13 10.4 10.8 13 8 14.5 5.2 13 3 10.4 3 7.1V3.2L8 1.3Z" stroke="currentColor" stroke-width="1.5"></path>
                            <path d="m5.7 7.7 1.5 1.5 3.2-3.4" stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                        Admin
                    </button>
                </div>
            </div>

            <div class="divider">
                <div class="divider-line"></div>
                <span>or</span>
                <div class="divider-line"></div>
            </div>

            <form class="account-form" id="signupForm" action="{{ url('/register') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Full Name" class="form-input" autocomplete="name" required>
                <input type="email" name="email" placeholder="Email Address" class="form-input" autocomplete="email" required>

                <div class="password-wrap">
                    <input type="password" id="password" name="password" placeholder="Password" class="form-input" autocomplete="new-password" minlength="8" required>
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

                <input type="hidden" name="role" id="selectedRole" value="client">

                <button type="submit" class="create-button" id="submitButton">Create Account</button>
            </form>

            <p class="login-text">
                Already have an account?
                <a href="{{ url('/login') }}">Log in</a>
            </p>

            <div class="social-links">
                <a href="#" aria-label="Facebook">f</a>
                <a href="#" aria-label="Twitter">x</a>
                <a href="#" aria-label="LinkedIn">in</a>
                <a href="#" aria-label="Instagram">◎</a>
            </div>
        </div>
    </section>
</div>

<script>
    const clientRole = document.getElementById('clientRole');
    const adminRole = document.getElementById('adminRole');
    const selectedRole = document.getElementById('selectedRole');
    const password = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');

    function selectRole(role) {
        selectedRole.value = role;
        clientRole.classList.toggle('active', role === 'client');
        adminRole.classList.toggle('active', role === 'admin');
    }

    clientRole.addEventListener('click', function () { selectRole('client'); });
    adminRole.addEventListener('click', function () { selectRole('admin'); });

    passwordToggle.addEventListener('click', function () {
        const isPassword = password.type === 'password';
        password.type = isPassword ? 'text' : 'password';
        eyeIcon.style.display = isPassword ? 'block' : 'none';
        eyeOffIcon.style.display = isPassword ? 'none' : 'block';
    });

    selectRole('client');
</script>
</body>
</html>
