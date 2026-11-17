@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <header class="form-header">
        <h1>Reset Password</h1>
        <p>Choose a new password for your account.</p>
    </header>

    @if ($errors->any())
        <div class="alert alert-danger auth-alert" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form class="account-form" method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <input
            type="email"
            name="email"
            id="email"
            placeholder="Email Address"
            autocomplete="username"
            class="form-input"
            value="{{ old('email', $request->email) }}"
            required
            autofocus>

        <div class="password-wrap">
            <input
                type="password"
                name="password"
                id="password"
                placeholder="New Password"
                autocomplete="new-password"
                class="form-input"
                required>
            <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>

        <div class="password-wrap">
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                placeholder="Confirm New Password"
                autocomplete="new-password"
                class="form-input"
                required>
            <button type="button" class="password-toggle" data-password-toggle="password_confirmation" aria-label="Show password">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>

        <button type="submit" class="login-button">
            Reset Password
        </button>
    </form>
@endsection
