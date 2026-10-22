<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Client Sign Up</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #102b21;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        .signup-container {
            display: flex;
            width: 100%;
            max-width: 1100px;
            min-height: 650px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
        }

        .left-panel {
            width: 45%;
            background: #102b21;
            color: white;
            padding: 45px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .left-panel h2 {
            font-size: 38px;
            line-height: 1.2;
            margin-bottom: 15px;
        }

        .left-panel p {
            color: #d9d9d9;
            line-height: 1.7;
        }

        .art-image {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            border-radius: 10px;
            margin-top: 25px;
        }

        .right-panel {
            width: 55%;
            padding: 40px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel h1 {
            font-size: 32px;
            font-weight: bold;
            color: #102b21;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 25px;
        }

        .role-label {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #374151;
        }

        .role-selector {
            display: flex;
            gap: 10px;
            padding: 5px;
            background: #f3f4f6;
            border-radius: 9px;
            margin-bottom: 25px;
        }

        .role-button {
            flex: 1;
            border: none;
            background: white;
            color: #374151;
            padding: 12px;
            border-radius: 7px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .role-button.active {
            background: #102b21;
            color: white;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
        }

        .form-control:focus {
            border-color: #102b21;
            box-shadow: 0 0 0 2px rgba(16, 43, 33, 0.12);
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 65px;
        }

        .show-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #102b21;
            font-size: 13px;
            font-weight: bold;
        }

        .submit-button {
            width: 100%;
            background: #102b21;
            color: white;
            border: none;
            border-radius: 7px;
            padding: 13px;
            font-weight: bold;
            margin-top: 10px;
        }

        .submit-button:hover {
            background: #1c4635;
        }

        .login-text {
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
            color: #6b7280;
        }

        .login-text a {
            color: #102b21;
            font-weight: bold;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            .signup-container {
                flex-direction: column;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                padding: 30px 24px;
            }

            .left-panel h2 {
                font-size: 28px;
            }

            .art-image {
                max-height: 180px;
            }
        }
    </style>
</head>

<body>
<div class="signup-container">

    <div class="left-panel">
        <div class="brand">ARTFOLIO</div>

        <div>
            <h2>ART LIVES HERE.</h2>
            <p>
                Discover artworks, explore creative collections,
                and find pieces that match your style.
            </p>

            <img
                class="art-image"
                src="https://images.unsplash.com/photo-1541961017774-22349e4a1262?auto=format&fit=crop&w=900&q=80"
                alt="Colorful artwork"
            >
        </div>

        <small>Discover art. Express yourself.</small>
    </div>

    <div class="right-panel">
        <h1 id="pageTitle">Client Sign Up</h1>
        <p class="subtitle" id="pageSubtitle">
            Create your account and explore ART FOLIO.
        </p>

        <div class="role-label">SELECT YOUR ROLE</div>

        <div class="role-selector">
            <button
                type="button"
                class="role-button active"
                id="clientRole"
                onclick="selectRole('client')"
            >
                👤 Client
            </button>

            <button
                type="button"
                class="role-button"
                id="adminRole"
                onclick="selectRole('admin')"
            >
                🛡️ Admin
            </button>
        </div>

        <form id="signupForm" action="{{ url('/client-register') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input
                    type="text"
                    id="fullName"
                    name="name"
                    class="form-control"
                    placeholder="Enter your full name"
                    value="{{ old('name', 'Krystel Lina') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email"
                    value="{{ old('email', 'tapsilog@gmail.com') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>

                <div class="password-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Create a password"
                        minlength="8"
                        required
                    >

                    <button
                        type="button"
                        class="show-password"
                        onclick="togglePassword()"
                        id="showPasswordButton"
                    >
                        Show
                    </button>
                </div>
            </div>

            <button type="submit" class="submit-button" id="submitButton">
                Create Client Account
            </button>
        </form>

        <p class="login-text">
            Already have an account?
            <a href="{{ url('/client-login') }}">Log in</a>
        </p>
    </div>

</div>

<script>
    let selectedRole = 'client';

    function selectRole(role) {
        selectedRole = role;

        document.getElementById('clientRole')
            .classList.toggle('active', role === 'client');

        document.getElementById('adminRole')
            .classList.toggle('active', role === 'admin');

        if (role === 'client') {
            document.getElementById('pageTitle').textContent = 'Client Sign Up';
            document.getElementById('pageSubtitle').textContent =
                'Create your account and explore ART FOLIO.';
            document.getElementById('submitButton').textContent =
                'Create Client Account';
        } else {
            document.getElementById('pageTitle').textContent = 'Admin Sign Up';
            document.getElementById('pageSubtitle').textContent =
                'Create an account for admin access.';
            document.getElementById('submitButton').textContent =
                'Create Admin Account';
        }
    }

    function togglePassword() {
        const password = document.getElementById('password');
        const button = document.getElementById('showPasswordButton');

        if (password.type === 'password') {
            password.type = 'text';
            button.textContent = 'Hide';
        } else {
            password.type = 'password';
            button.textContent = 'Show';
        }
    }

    document.getElementById('signupForm').addEventListener('submit', function(event) {
        if (selectedRole !== 'client') {
            event.preventDefault();
            window.location.href = "{{ url('/admin-signup') }}";
        }
    });
</script>

</body>
</html>