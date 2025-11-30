@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2">
        <div class="container mb-4">
            <div class="row">
                <div class="col-lg-6 mx-auto px-4" style="border-radius: 0.5rem; border: 1px solid #CCC;">
                    <h2 class="mx-auto text-center mb-0 mt-4"> Forgot Password </h2> <br>
                    <div class="mb-4">{{ __('Forgot your password? No problem.') }}</div>
                    <div class="registration-form mb-4">
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3"> <input type="email" class="form-control" id="email" name="email" placeholder="Email" required> </div>
                            <div class="text-end mt-4"> <button type="submit" class="btn btn-primary">{{ __('Email Password Reset Link') }}</button> </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
