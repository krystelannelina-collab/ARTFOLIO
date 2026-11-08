<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - @yield('title')</title>

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
            onerror="this.remove();">
    </div>

    <section class="form-panel">
        <div class="form-content">

            <img
                src="@yield('logo', 'https://api.builder.io/api/v1/image/assets/TEMP/c33f41d46e0fc49a2584e3b9831f75f33bfbd374?width=549')"
                alt="ARTFOLIO Logo"
                class="logo">

            @yield('content')
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
    document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
        const input = document.getElementById(toggle.dataset.passwordToggle);
        const icon = toggle.querySelector('i');

        toggle.addEventListener('click', () => {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.className = isHidden ? 'bi bi-eye' : 'bi bi-eye-slash';
            toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
    });
</script>
@stack('scripts')
</body>
</html>
