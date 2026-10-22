<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ARTFOLIO</title>
    <meta name="description" content="Create your account">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #102B21;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(16px, 7.4vw, 105px);
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
            position: relative;
            flex: 1;
            min-width: 0;
        }

        .hero-panel img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-panel {
            width: 584px;
            flex-shrink: 0;
            min-height: 839px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 63px 48px 32px;
            border-radius: 30px;
            background: #fff;
        }

        .logo {
            width: 274px;
            max-width: 100%;
            height: auto;
            transform: rotate(-0.208deg);
        }

        .form-header {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding-bottom: 24px;
        }

        .form-header h1 {
            padding-top: 12px;
            margin: 0;
            color: #111827;
            font-size: 26px;
            font-weight: 700;
            line-height: 32px;
            letter-spacing: -0.65px;
            text-align: center;
        }

        .form-header p {
            margin: 0;
            color: #6B7280;
            font-size: 12px;
            line-height: 16px;
            text-align: center;
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
            line-height: 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
            cursor: pointer;
        }

        .google-button:hover {
            background: #f9fafb;
        }

        .google-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
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
            line-height: 16.5px;
            text-decoration: none;
            cursor: pointer;
        }

        .role-button.active {
            border-color: #102B21;
            outline: 1px solid #102B21;
            outline-offset: -1px;
        }

        .role-button svg {
            display: block;
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

        .form-input::placeholder {
            color: #9CA3AF;
        }

        .form-input:focus {
            border-color: #102B21;
            box-shadow: 0 0 0 2px rgba(16,43,33,.1);
        }

        .password-wrap {
            position: relative;
            padding-bottom: 8px;
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

        .create-button {
            width: 100%;
            padding: 10px 16px;
            border: 0;
            border-radius: 50px;
            background: #102B21;
            color: white;
            font-size: 13px;
            font-weight: 600;
            line-height: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1),
                        0 2px 4px -2px rgba(0,0,0,.1);
            transition: background .2s;
            cursor: pointer;
        }

        .create-button:hover {
            background: #16402f;
        }

        .login-text {
            padding-top: 16px;
            margin: 0;
            color: #6B7280;
            font-size: 12px;
            line-height: 16px;
            text-align: center;
        }

        .login-text a {
            color: #FF383C;
            font-weight: 500;
            text-decoration: none;
        }

        .login-text a:hover {
            text-decoration: underline;
        }

        .social-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding-top: 20px;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            color: #9CA3AF;
        }

        .social-links svg {
            display: block;
        }

        @media (max-width: 991px) {
            .page-wrapper {
                padding: 32px;
            }

            .main-card {
                max-width: 584px;
            }

            .hero-panel {
                display: none;
            }

            .form-panel {
                width: 100%;
            }
        }

        @media (max-width: 575px) {
            .page-wrapper {
                padding: 16px;
            }

            .form-panel {
                min-height: auto;
                padding: 40px 24px 32px;
            }

            .main-card,
            .form-panel {
                border-radius: 24px;
            }
        }
    </style>
</head>

<body>

<div class="page-wrapper">
    <div class="main-card">

        <!-- LEFT HERO IMAGE -->
        <div class="hero-panel">
            <img
                src="https://api.builder.io/api/v1/image/assets/TEMP/bbe1f71b84adbf86b9cb159ffe42d386fe34f525?width=1192"
                alt="">
        </div>

        <!-- RIGHT FORM PANEL -->
        <section class="form-panel">

            <img
                src="https://api.builder.io/api/v1/image/assets/TEMP/fc8de5f172a6deead0a1ba38f30a585659cb87d6?width=549"
                alt="Logo"
                class="logo">

            <header class="form-header">
                <h1>Create Account</h1>
                <p>Join as a creative talent</p>
            </header>

            <button type="button" class="google-button">
                Sign up with Google

                <svg class="google-icon" viewBox="0 0 16 16" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.04 8.16675C15.04 7.64675 14.9933 7.14675 14.9067 6.66675H8V9.50675H11.9467C11.7733 10.4201 11.2533 11.1934 10.4733 11.7134V13.5601H12.8533C14.24 12.2801 15.04 10.4001 15.04 8.16675" fill="#4285F4"/>
                    <path d="M7.99979 15.3332C9.97979 15.3332 11.6398 14.6799 12.8531 13.5599L10.4731 11.7132C9.81979 12.1532 8.98646 12.4199 7.99979 12.4199C6.09313 12.4199 4.47312 11.1332 3.89313 9.3999H1.45312V11.2932C2.65979 13.6866 5.13313 15.3332 7.99979 15.3332" fill="#34A853"/>
                    <path d="M3.89366 9.39345C3.74699 8.95345 3.66033 8.48679 3.66033 8.00012C3.66033 7.51345 3.74699 7.04679 3.89366 6.60679V4.70679H1.45366C.953659 5.70012.666992 6.81345.666992 8.00012C.666992 9.18679.953659 10.3001 1.45366 11.2935L3.35366 9.81345L3.89366 9.39345" fill="#FBBC05"/>
                    <path d="M7.99979 3.58675C9.07979 3.58675 10.0398 3.96008 10.8065 4.68008L12.9065 2.58008C11.6331 1.39341 9.97979 0.666748 7.99979 0.666748C5.13313 0.666748 2.65979 2.31341 1.45312 4.70675L3.89313 6.60008C4.47313 4.86675 6.09313 3.58675 7.99979 3.58675" fill="#EA4335"/>
                </svg>
            </button>

            <!-- ADMIN PORTAL ACCESS -->
            <div class="admin-box">
                <div class="admin-heading">
                    <svg width="10" height="12" viewBox="0 0 10 12"
                         fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.66667 11.6667C3.31528 11.3264 2.19965 10.551 1.31979 9.34062C0.439931 8.13021 0 6.78611 0 5.30833V1.75L4.66667 0L9.33333 1.75V5.30833C9.33333 6.78611 8.8934 8.13021 8.01354 9.34062C7.13368 10.551 6.01806 11.3264 4.66667 11.6667Z" fill="#F25100"/>
                    </svg>

                    <span>Admin Portal Access</span>
                </div>

                <div class="role-buttons">

                    <!-- ADMIN SIGN UP LINK -->
                    <a href="{{ url('/admin-signup') }}" class="role-button">
                        <svg width="11" height="14" viewBox="0 0 11 14"
                             fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.33333 7.33333C4.67778 7.33333 4.125 7.10833 3.675 6.65833C3.225 6.20833 3 5.65556 3 5C3 4.34444 3.225 3.79167 3.675 3.34167C4.125 2.89167 4.67778 2.66667 5.33333 2.66667C5.98889 2.66667 6.54167 2.89167 6.99167 3.34167C7.44167 3.79167 7.66667 4.34444 7.66667 5C7.66667 5.65556 7.44167 6.20833 6.99167 6.65833C6.54167 7.10833 5.98889 7.33333 5.33333 7.33333Z" fill="#374151"/>
                        </svg>
                        Admin
                    </a>

                    <!-- CLIENT SIGN UP LINK -->
                    <a href="{{ url('/client-signup') }}" class="role-button active">
                        <svg width="18" height="17" viewBox="0 0 18 17"
                             fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.3247 6.53852H11.6679C11.3411 6.53852 11.0767 6.24886 11.0767 5.89186V5.87813C11.0767 5.52113 11.3411 5.23148 11.6679 5.23148H15.3247C15.6542 5.23148 15.9228 5.52571 15.9228 5.88533C15.9228 6.24494 15.6542 6.53917 15.3247 6.53917V6.53852ZM6.92283 8.50006C6.18839 8.50006 5.48402 8.22451 4.96469 7.73403C4.44536 7.24355 4.1536 6.57831 4.1536 5.88467C4.1536 5.19103 4.44536 4.5258 4.96469 4.03532C5.48402 3.54484 6.18839 3.26929 6.92283 3.26929C7.65728 3.26929 8.36164 3.54484 8.88097 4.03532C9.40031 4.5258 9.69206 5.19103 9.69206 5.88467C9.69206 6.57831 9.40031 7.24355 8.88097 7.73403C8.36164 8.22451 7.65728 8.50006 6.92283 8.50006Z" fill="black"/>
                        </svg>
                        Client
                    </a>

                </div>
            </div>

            <!-- OR DIVIDER -->
            <div class="divider">
                <div class="divider-line"></div>
                <span>or</span>
                <div class="divider-line"></div>
            </div>

            <!-- CREATE ACCOUNT FORM -->
            <form class="account-form" id="accountForm">

                <input
                    type="text"
                    name="name"
                    placeholder="Full Name"
                    autocomplete="name"
                    class="form-input">

                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    autocomplete="email"
                    class="form-input">

                <div class="password-wrap">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Password"
                        autocomplete="new-password"
                        class="form-input">

                    <button
                        type="button"
                        id="togglePassword"
                        aria-label="Toggle password visibility"
                        class="password-toggle">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </button>
                </div>

                <button type="submit" class="create-button">
                    Create Account
                </button>
            </form>

            <p class="login-text">
                Already have an account?
                <a href="{{ url('/login') }}">Log in</a>
            </p>

            <!-- SOCIAL LINKS -->
            <div class="social-links">

                <a href="#" aria-label="Facebook">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M14 7.0426C14 3.17685 10.8657 .0426025 7 .0426025C3.13425 .0426025 0 3.17685 0 7.0426C0 10.5368 2.55967 13.4324 5.90625 13.9574V9.06619H4.12883V7.04202H5.90625V5.50085C5.90625 3.74677 6.95158 2.77727 8.5505 2.77727C9.31583 2.77727 10.1173 2.91435 10.1173 2.91435V4.63694H9.23417C8.36442 4.63694 8.09317 5.17652 8.09317 5.7301V7.0426H10.0345L9.72417 9.06677H8.09317V13.958C11.4403 13.4324 14 10.5362 14 7.0426" fill="#9CA3AF"/>
                    </svg>
                </a>

                <a href="#" aria-label="Twitter">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M10.6423 1.3125H12.5719L8.35619 6.13083L13.3157 12.6875H9.43244L6.39094 8.71092L2.91078 12.6875H.979945L5.48911 7.53375L.731445 1.3125H4.71328L7.46253 4.94725L10.6423 1.3125Z" fill="#9CA3AF"/>
                    </svg>
                </a>

                <a href="#" aria-label="LinkedIn">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M11.0833 0H2.91667C1.30608 0 0 1.30608 0 2.91667V11.0833C0 12.6939 1.30608 14 2.91667 14H11.0833C12.6945 14 14 12.6939 14 11.0833V2.91667C14 1.30608 12.6945 0 11.0833 0ZM4.66667 11.0833H2.91667V4.66667H4.66667V11.0833ZM3.79167 3.927C3.22817 3.927 2.77083 3.46617 2.77083 2.898C2.77083 2.32983 3.22817 1.869 3.79167 1.869C4.35575 1.869 4.8125 2.32983 4.8125 2.898C4.8125 3.46617 4.35575 3.927 3.79167 3.927ZM11.6667 11.0833H9.91667V7.81433C9.91667 5.84967 7.58333 5.99842 7.58333 7.81433V11.0833H5.83333V4.66667H7.58333V5.69625C8.39767 4.18775 11.6667 4.07633 11.6667 7.14058V11.0833Z" fill="#9CA3AF"/>
                    </svg>
                </a>

                <a href="#" aria-label="Instagram">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 1.26175C8.869 1.26175 9.09067 1.26875 9.82917 1.30258C11.7262 1.38892 12.6122 2.289 12.6986 4.172C12.7324 4.90992 12.7388 5.13158 12.7388 7.00058C12.7388 8.87017 12.7318 9.09125 12.6986 9.82917C12.6117 11.7104 11.7279 12.6122 9.82917 12.6986C9.09067 12.7324 8.87017 12.7394 7 12.7394C5.131 12.7394 4.90933 12.7324 4.17142 12.6986C2.26975 12.6117 1.38833 11.7075 1.302 9.82858C1.26817 9.09067 1.26117 8.86958 1.26117 7C1.26117 5.131 1.26875 4.90992 1.302 4.17142C1.38892 2.289 2.27267 1.38833 4.17142 1.302C4.90992 1.26875 5.131 1.26175 7 1.26175ZM7 3.4055C5.01492 3.4055 3.4055 5.01492 3.4055 7C3.4055 8.98508 5.01492 10.5951 7 10.5951C8.98508 10.5951 10.5945 8.98567 10.5945 7C10.5945 5.01492 8.98508 3.4055 7 3.4055ZM7 9.33333C5.71142 9.33333 4.66667 8.28917 4.66667 7C4.66667 5.71142 5.71142 4.66667 7 4.66667C8.28858 4.66667 9.33333 5.71142 9.33333 7C9.33333 8.28917 8.28858 9.33333 7 9.33333Z" fill="#9CA3AF"/>
                    </svg>
                </a>

            </div>
        </section>
    </div>
</div>

<script>
    // Show or hide password
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const show = passwordInput.type === 'password';

        passwordInput.type = show ? 'text' : 'password';
        eyeIcon.className = show ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    // Prevent form submission for now
    document.getElementById('accountForm').addEventListener('submit', function (event) {
        event.preventDefault();
    });
</script>

</body>
</html>