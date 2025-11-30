@extends('layouts.main')

@section('content')
    <div class="content-wrapper mb-4">
        <div class="col-xl-6 col-lg-9 col-md-10 col-sm-11 mx-auto p-4">
            <h2 class="mx-auto text-center mb-3"> Need to contact the team? </h2>
            <p class="mt-0 mb-3" style="text-align: left !important"> Need to reach out to our team? Contact us via live chat (bottom-right) or fill out the form below. We'll respond within 24 hours.</p>


            <form action="{{route('forms.contact')}}" id="contact-form" method="post">
                <div class="form-group mb-3"> <input type="text" class="form-control" id="name" name="name" placeholder="Name" required> </div>
                <div class="form-group mb-3"> <input type="email" class="form-control" id="email" name="email" placeholder="Email" required> </div>
                <div class="form-group mb-3"> <textarea required="" name="text" id="text" cols="30" rows="5" placeholder="Message" class="form-control"></textarea> </div>
                <div class="row">
                    <div class="col-sm-9">
                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display() !!}
                    </div>
                    <div class="col-sm-3">
                        <div class="text-right">
                            <button type="submit" disabled class="btn btn-primary btn-sm">
                                Send Message
                            </button>
                        </div>
                    </div>
                </div>
                {{csrf_field()}}
                <input type="hidden" name="time" value="{{md5('random')}}">
            </form>
        </div>
    </div>
@endsection

@push('bottom')
    <script>
        setTimeout(function (e) {
            var $form = $('#contact-form');
            $form.find('[name=time]').val('{{md5('time-delay')}}');
            $form.find('button[type=submit]').prop('disabled', false);
        }, 10000);
    </script>
@endpush
