@extends(Auth::check() ? 'layouts.dashboard' : 'layouts.main')

@section('page-title', 'Contact Support')

@section('content')
    @if(Auth::check())
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h2 class="dashboard-card-title">Contact Support</h2>
            </div>
            <p class="mb-4" style="color: var(--text-secondary);">Have a question? Fill out the form below and we'll get back to you within 24 hours.</p>

            <div class="row">
                <div class="col-lg-8">
                    <form action="{{route('forms.contact')}}" id="contact-form" method="post">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" placeholder="Your name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" placeholder="you@example.com" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="text" class="form-label">Message</label>
                            <textarea required name="text" id="text" cols="30" rows="5" placeholder="How can we help?" class="form-control"></textarea>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-8 mb-3 mb-sm-0">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" disabled class="btn btn-primary w-100">
                                    Send Message
                                </button>
                            </div>
                        </div>
                        {{csrf_field()}}
                        <input type="hidden" name="time" value="{{md5('random')}}">
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="content-wrapper-2">
            <div class="container">
                <div class="col-lg-6 mx-auto" style="background: white; border-radius: 16px; padding: 48px; border: 1px solid #e8e8e8;">
                    <h1 class="mb-2" style="font-size: 32px;">Contact us</h1>
                    <p class="mb-4" style="color: #6b6b6b;">Have a question? Fill out the form below and we'll get back to you within 24 hours.</p>

                    <form action="{{route('forms.contact')}}" id="contact-form" method="post">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label" style="font-size: 14px; font-weight: 500; margin-bottom: 6px;">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Your name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label" style="font-size: 14px; font-weight: 500; margin-bottom: 6px;">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="text" class="form-label" style="font-size: 14px; font-weight: 500; margin-bottom: 6px;">Message</label>
                            <textarea required name="text" id="text" cols="30" rows="5" placeholder="How can we help?" class="form-control"></textarea>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-8 mb-3 mb-sm-0">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" disabled class="btn btn-primary w-100">
                                    Send Message
                                </button>
                            </div>
                        </div>
                        {{csrf_field()}}
                        <input type="hidden" name="time" value="{{md5('random')}}">
                    </form>
                </div>
            </div>
        </div>
    @endif
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
