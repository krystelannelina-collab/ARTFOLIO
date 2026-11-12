@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <header class="form-header">
        <h1>Forgot Password</h1>
        <p>Enter your email address and we will send you a link to choose a new password.</p>
    </header>

    @if (session('status'))
        <div class="alert alert-info auth-alert" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger auth-alert" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form class="account-form" method="POST" action="{{ route('password.email') }}">
        @csrf
        <input
            type="email"
            name="email"
            id="email"
            placeholder="Email Address"
            autocomplete="username"
            class="form-input"
            value="{{ old('email') }}"
            required
            autofocus>

        <button type="submit" class="login-button">
            Email Password Reset Link
        </button>
    </form>

    <p class="signup-text">
        Remembered it?
        <a href="{{ route('login') }}">Back to Log In</a>
    </p>
@endsection
