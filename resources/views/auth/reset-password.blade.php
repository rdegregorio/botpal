@extends('layouts.auth')

@section('content')
    <h1 class="auth-title">Set new password</h1>
    <p class="auth-subtitle">Create a new password for your account</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Enter your email" required autofocus>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="btn-submit">Reset password</button>
    </form>
@endsection

@section('footer')
    <div class="auth-footer">
        Remember your password? <a href="{{ route('login') }}">Sign in</a>
    </div>
@endsection
