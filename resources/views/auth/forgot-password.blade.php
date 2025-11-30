@extends('layouts.auth')

@section('content')
    <h1 class="auth-title">Reset password</h1>
    <p class="auth-subtitle">Enter your email and we'll send you a reset link</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
        </div>

        <button type="submit" class="btn-submit">Send reset link</button>
    </form>
@endsection

@section('footer')
    <div class="auth-footer">
        Remember your password? <a href="{{ route('login') }}">Sign in</a>
    </div>
@endsection
