@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
    <header class="form-header">
        <h1>Verify Your Email</h1>
        <p>Thanks for signing up! Please click the link we just emailed you. If it did not arrive, we can send another.</p>
    </header>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success auth-alert" role="status">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <form class="account-form" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="login-button">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="text-center mt-3">
        @csrf
        <button type="submit" class="btn btn-link auth-link">Log Out</button>
    </form>
@endsection
