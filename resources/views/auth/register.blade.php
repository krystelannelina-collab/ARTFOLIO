@extends('layouts.auth')

@section('title', 'Sign Up')
@section('logo', 'https://api.builder.io/api/v1/image/assets/TEMP/fc8de5f172a6deead0a1ba38f30a585659cb87d6?width=549')

@section('content')
    <header class="form-header">
        <h1>Create Account</h1>
        <p>Join as a creative talent</p>
    </header>

    <div class="role-box" role="note">
        <div class="role-heading">
            <i class="bi bi-shield-fill" style="color:#F25100"></i>
            <span>Client Account Registration</span>
        </div>
        <p class="mb-0 mt-2 text-secondary" style="font-size:12px">
            Public registration creates a client account. Administrator access must be granted by a system administrator.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger auth-alert" role="alert">
            Please fix the {{ \Illuminate\Support\Str::plural('field', $errors->count()) }} marked below.
        </div>
    @endif

    <form class="account-form" id="signupForm" action="{{ route('register') }}" method="POST">
        @csrf
        <input
            type="text"
            name="name"
            placeholder="Full Name"
            class="form-input"
            autocomplete="name"
            value="{{ old('name') }}"
            required
            autofocus>
        @error('name')<small class="field-error" role="alert">{{ $message }}</small>@enderror

        <input
            type="text"
            name="username"
            placeholder="Username"
            class="form-input"
            autocomplete="username"
            value="{{ old('username') }}"
            minlength="3"
            maxlength="30"
            pattern="[A-Za-z0-9_\-]+"
            title="3 to 30 letters, numbers, dashes, or underscores"
            required>
        @error('username')<small class="field-error" role="alert">{{ $message }}</small>@enderror

        <input
            type="email"
            name="email"
            placeholder="Email Address"
            class="form-input"
            autocomplete="email"
            value="{{ old('email') }}"
            required>
        @error('email')<small class="field-error" role="alert">{{ $message }}</small>@enderror

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
            <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>
        @error('password')<small class="field-error" role="alert">{{ $message }}</small>@enderror

        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            placeholder="Confirm password"
            class="form-input"
            autocomplete="new-password"
            minlength="8"
            required>
        <small class="field-error" id="passwordMatchError" role="alert" hidden>Passwords do not match.</small>

        <label class="terms-check">
            <input type="checkbox" name="terms" value="1" @checked(old('terms')) required>
            <span>I agree to the ARTFOLIO Terms and Conditions and Privacy Policy.</span>
        </label>
        @error('terms')<small class="field-error" role="alert">{{ $message }}</small>@enderror

        <button type="submit" class="create-button" id="submitButton">Create Account</button>
    </form>

    <p class="login-text">
        Already have an account?
        <a href="{{ route('login') }}">Log in</a>
    </p>
@endsection

@push('scripts')
    <script>
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const passwordMatchError = document.getElementById('passwordMatchError');

        function checkPasswordsMatch() {
            const isMismatch = passwordConfirmation.value !== '' && passwordConfirmation.value !== password.value;
            passwordConfirmation.setCustomValidity(isMismatch ? 'Passwords do not match.' : '');
            passwordMatchError.hidden = ! isMismatch;
        }

        password.addEventListener('input', checkPasswordsMatch);
        passwordConfirmation.addEventListener('input', checkPasswordsMatch);
    </script>
@endpush
