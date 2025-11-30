@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2">
        <div class="container mb-4">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <h4 class="mx-auto text-center mb-0"> Confirm Password </h4> <br>
                    <div class="mb-4">{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</div>
                    <div class="registration-form mb-4">
                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf
                            <div class="mb-3"> <input type="password" class="form-control" id="password" name="password" placeholder="Password" required> </div>
                            <div class="text-end mt-4"> <button type="submit" class="btn btn-primary">{{ __('Confirm') }}</button> </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
