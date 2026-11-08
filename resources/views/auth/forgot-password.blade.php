@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <header class="form-header">
        <h1>Forgot Password</h1>
        <p>Enter your email address and we will send you a link to choose a new password.</p>
    </header>

    @if (session('status'))
        <div class="alert alert-success auth-alert" role="status">{{ session('status') }}</div>
    @endif

    <form class="account-form" method="POST" action="{{ route('password.email') }}">
        @csrf
        <input
            type="email"
            name="email"
            placeholder="Email Address"
            class="form-input"
            autocomplete="email"
            value="{{ old('email') }}"
            required
            autofocus>
        @error('email')<small class="field-error" role="alert">{{ $message }}</small>@enderror

        <button type="submit" class="login-button">Email Password Reset Link</button>
    </form>

    <p class="signup-text">
        Remembered it?
        <a href="{{ route('login') }}">Back to log in</a>
    </p>
@endsection
