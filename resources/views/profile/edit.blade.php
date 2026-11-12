<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | ARTFOLIO</title>
    <meta name="description" content="Manage your ARTFOLIO account details and password.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
</head>
<body>
<div class="app-layout">
    @include('partials.site-header', ['active' => 'profile'])

    <main class="main-content orders-content">
        <header class="orders-heading">
            <span class="orders-eyebrow">YOUR ACCOUNT</span>
            <h1>Profile.</h1>
            <p>Update your account details and password.</p>
        </header>

        <div class="row g-4">
            <div class="col-12 col-xl-6">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="col-12 col-xl-6">
                @include('profile.partials.update-password-form')
            </div>
            <div class="col-12">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </main>
</div>

@if ($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('confirm-user-deletion')).show();
        });
    </script>
@endif
</body>
</html>
