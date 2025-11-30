@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2">
        <div class="container mb-4">
            <div class="row">
                <div class="col-lg-6 mx-auto px-4" style="border-radius: 0.5rem;border: 1px solid #CCC;">
                    <div class="steps mb-4 mt-4">
                    <!--
                        <div class="circle active">1</div>
                        <div class="circle">2</div>
                        <div class="circle">3</div>
                    -->
                    </div>
                    <h2 class="mx-auto text-center mb-0"> Register </h2> <br>
                    <div class="registration-form mb-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3"> <input type="text" class="form-control" id="name" name="name" placeholder="Name" required> </div>
                            <div class="mb-3"> <input type="email" class="form-control" id="email" name="email" placeholder="Email" required> </div>
                            <div class="mb-3"> <input type="password" class="form-control" id="password" name="password" placeholder="Password" required> </div>
                            <div class="mb-3"> <input type="password" class="form-control" id="confirm-password" name="password_confirmation" placeholder="Confirm Password" required> </div>
                            <div class="mb-3 d-flex justify-content-end">
                                <div class="form-check"> <input type="checkbox" class="form-check-input" name="tos" id="terms" required> <label class="form-check-label" for="terms" style="font-size: 12px">Accept Terms and Conditions</label> </div>
                            </div>
                            <div class="text-end mt-4"> <button type="submit" class="btn btn-primary">Next</button> </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
