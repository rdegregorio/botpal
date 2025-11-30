@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2">
        <div class="container mb-4">
            <div class="row">
                <div class="col-lg-6 mx-auto px-4" style="border-radius: 0.5rem; border: 1px solid #CCC;">
                    <h2 class="mx-auto text-center mb-0 mt-4"> Login </h2> <br>
                    <div class="registration-form mb-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3"> <input type="email" class="form-control" id="email" name="email" placeholder="Email" required> </div>
                            <div class="mb-3"> <input type="password" class="form-control" id="password" name="password" placeholder="Password" required> </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" class="" name="remember">
                                    <span>{{ __('Remember me') }}</span>
                                </label>
                                <a href="{{route('password.email')}}">Forgot password</a>
                            </div>
                            <div class="text-end mt-4"> <button type="submit" class="btn btn-primary">Login</button> </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
