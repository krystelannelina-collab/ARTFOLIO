@extends('layouts.auth')

@section('title', 'Confirm Password')

@section('content')
    <header class="form-header">
        <h1>Confirm Password</h1>
        <p>This is a secure area. Please confirm your password before continuing.</p>
    </header>

    @if ($errors->any())
        <div class="alert alert-danger auth-alert" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form class="account-form" method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="password-wrap">
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Password"
                autocomplete="current-password"
                class="form-input"
                required
                autofocus>
            <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>

        <button type="submit" class="login-button">
            Confirm
        </button>
    </form>
@endsection
