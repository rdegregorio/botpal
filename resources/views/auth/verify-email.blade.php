@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2">
        <div class="container mb-4">
            <div class="row">
                <div class="col-lg-6 mx-auto px-4" style="border-radius: 0.5rem; border: 1px solid #CCC;">
                    <h4 class="mx-auto text-center mb-0 mt-4"> Verify Email </h4> <br>
                    <div class="mb-4">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif

                    <div class="registration-form mb-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <div class="text-end mt-4"> <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button> </div>
                        </form>
                    </div>

                    <div class="registration-form mb-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div class="text-end mt-4"> <button type="submit" class="btn btn-primary">{{ __('Log Out') }}</button> </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
