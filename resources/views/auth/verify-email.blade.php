@extends('layouts.auth')

@section('content')
    <h1 class="auth-title">Verify your email</h1>
    <p class="auth-subtitle">We've sent a verification link to your email address. Please check your inbox.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success" style="margin-bottom: 24px;">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom: 16px;">
        @csrf
        <button type="submit" class="btn-submit">Resend verification email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-google">Log out</button>
    </form>
@endsection
