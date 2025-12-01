@extends('layouts.landing')

@section('title', 'Contact Us - BotPal')

@section('content')
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-inner">
                <a href="/" class="navbar-logo">
                    <span class="navbar-logo-icon"><i class="bi bi-chat-dots-fill"></i></span>
                    BotPal
                </a>
                <div class="navbar-right" id="navbarMenu">
                    <div class="navbar-links">
                        <a href="/#features" class="navbar-link">Features</a>
                        <a href="/#how-it-works" class="navbar-link">How it works</a>
                        <a href="/#pricing" class="navbar-link">Pricing</a>
                        <a href="/#faq" class="navbar-link">FAQ</a>
                    </div>
                    <div class="navbar-cta">
                        @auth
                            <a href="/dashboard" class="navbar-link">Account</a>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-primary">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                        @else
                            <a href="{{ route('login') }}" class="navbar-link">Log in</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Get started</a>
                        @endauth
                    </div>
                </div>
                <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle menu">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section class="hero" style="padding-top: 140px; padding-bottom: 60px;">
        <div class="container">
            <div style="background: white; border-radius: 16px; padding: 48px; border: 1px solid #e8e8e8; max-width: 600px; margin: 0 auto; text-align: left;">
                <h1 style="font-size: 32px; font-weight: 600; margin-bottom: 8px;">Contact Us</h1>
                <p style="color: #6b6b6b; margin-bottom: 32px;">Have a question? Fill out the form below and we'll get back to you within 24 hours.</p>

                <form action="{{route('forms.contact')}}" id="contact-form" method="post">
                    <div class="mb-3">
                        <label for="name" style="font-size: 14px; font-weight: 500; margin-bottom: 6px; display: block;">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()?->name }}" placeholder="Your name" required style="padding: 12px; border-radius: 8px; border: 1px solid #e8e8e8; width: 100%;">
                    </div>
                    <div class="mb-3">
                        <label for="email" style="font-size: 14px; font-weight: 500; margin-bottom: 6px; display: block;">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()?->email }}" placeholder="you@example.com" required style="padding: 12px; border-radius: 8px; border: 1px solid #e8e8e8; width: 100%;">
                    </div>
                    <div class="mb-4">
                        <label for="text" style="font-size: 14px; font-weight: 500; margin-bottom: 6px; display: block;">Message</label>
                        <textarea required name="text" id="text" cols="30" rows="5" placeholder="How can we help?" style="padding: 12px; border-radius: 8px; border: 1px solid #e8e8e8; width: 100%;"></textarea>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-sm-8 mb-3 mb-sm-0">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" disabled class="btn btn-primary" style="width: 100%; background: #1a1a1a; border-color: #1a1a1a;">
                                Send Message
                            </button>
                        </div>
                    </div>
                    {{csrf_field()}}
                    <input type="hidden" name="time" value="{{md5('random')}}">
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="/" class="navbar-logo">
                        <span class="navbar-logo-icon"><i class="bi bi-chat-dots-fill"></i></span>
                        BotPal
                    </a>
                    <p>AI-powered chatbots that help businesses deliver exceptional customer support.</p>
                </div>
                <div class="footer-column">
                    <h4>Product</h4>
                    <ul>
                        <li><a href="/#features">Features</a></li>
                        <li><a href="/#pricing">Pricing</a></li>
                        <li><a href="/#faq">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="{{ route('pages.about') }}">About</a></li>
                        <li><a href="{{ route('pages.contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="{{ route('pages.privacy') }}">Privacy</a></li>
                        <li><a href="{{ route('pages.terms') }}">Terms</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="{{ route('pages.contact') }}">Help Center</a></li>
                        <li><a href="{{ route('pages.contact') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} BotPal. All rights reserved.</p>
            </div>
        </div>
    </footer>
@endsection

@push('scripts')
<script>
const navbarToggle = document.getElementById('navbarToggle');
const navbarMenu = document.getElementById('navbarMenu');
if (navbarToggle && navbarMenu) {
    navbarToggle.addEventListener('click', function() {
        navbarMenu.classList.toggle('active');
        const icon = this.querySelector('i');
        icon.classList.toggle('bi-list');
        icon.classList.toggle('bi-x-lg');
    });
}

setTimeout(function (e) {
    var form = document.getElementById('contact-form');
    form.querySelector('[name=time]').value = '{{md5('time-delay')}}';
    form.querySelector('button[type=submit]').disabled = false;
}, 10000);
</script>
@endpush
