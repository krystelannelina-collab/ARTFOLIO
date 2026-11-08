@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <header class="form-header">
        <h1>Reset Password</h1>
        <p>Choose a new password for your ARTFOLIO account.</p>
    </header>

    <form class="account-form" method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <input
            type="email"
            name="email"
            placeholder="Email Address"
            class="form-input"
            autocomplete="username"
            value="{{ old('email', $request->email) }}"
            required
            autofocus>
        @error('email')<small class="field-error" role="alert">{{ $message }}</small>@enderror

        <div class="password-wrap">
            <input
                type="password"
                id="password"
                name="password"
                placeholder="New password (min. 8 characters)"
                class="form-input"
                autocomplete="new-password"
                required>
            <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>
        @error('password')<small class="field-error" role="alert">{{ $message }}</small>@enderror

        <input
            type="password"
            name="password_confirmation"
            placeholder="Confirm new password"
            class="form-input"
            autocomplete="new-password"
            required>
        @error('password_confirmation')<small class="field-error" role="alert">{{ $message }}</small>@enderror

        <button type="submit" class="login-button">Reset Password</button>
    </form>
@endsection
