@extends('layouts.auth')

@section('title', 'Confirm Password')

@section('content')
    <header class="form-header">
        <h1>Confirm Password</h1>
        <p>This is a secure area. Please confirm your password before continuing.</p>
    </header>

    <form class="account-form" method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="password-wrap">
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Password"
                class="form-input"
                autocomplete="current-password"
                required
                autofocus>
            <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>
        @error('password')<small class="field-error" role="alert">{{ $message }}</small>@enderror

        <button type="submit" class="login-button">Confirm</button>
    </form>
@endsection
