@extends('layouts.auth')

@section('content')
    <h1 class="auth-title">Confirm password</h1>
    <p class="auth-subtitle">This is a secure area. Please confirm your password to continue.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required autofocus>
        </div>

        <button type="submit" class="btn-submit">Confirm</button>
    </form>
@endsection

@section('footer')
    <div class="auth-footer">
        <a href="{{ route('login') }}">Back to sign in</a>
    </div>
@endsection
