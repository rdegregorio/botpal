@extends('layouts.landing')

@section('title', 'Privacy Policy - aisupport.bot')

@section('content')
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-inner">
                <a href="/" class="navbar-logo">
                    <span class="navbar-logo-icon"><i class="bi bi-chat-dots-fill"></i></span>
                    aisupport.bot
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
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="navbar-link">Logout</a>
                            <a href="/dashboard" class="btn btn-primary">Account</a>
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
            <div style="background: white; border-radius: 16px; padding: 48px; border: 1px solid #e8e8e8; max-width: 900px; margin: 0 auto; text-align: left;">
                <h1 style="font-size: 32px; font-weight: 600; margin-bottom: 8px;">Privacy Policy</h1>
                <p style="color: #6b6b6b; margin-bottom: 32px;">Effective date: August 21, 2023</p>

                <div style="color: #444; font-size: 15px; line-height: 1.7;">
                    <p class="mb-3">SNAPI LLC ("us", "we", or "our") operates the aisupport.bot website (hereinafter referred to as the "Service").</p>
                    <p class="mb-3">This page informs you of our policies regarding the collection, use and disclosure of personal data when you use our Service and the choices you have associated with that data.</p>
                    <p class="mb-4">We use your data to provide and improve the Service. By using the Service, you agree to the collection and use of information in accordance with this policy.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Definitions</h3>
                    <p class="mb-2"><strong>Service</strong> - The aisupport.bot website operated by SNAPI LLC</p>
                    <p class="mb-2"><strong>Personal Data</strong> - Data about a living individual who can be identified from those data</p>
                    <p class="mb-2"><strong>Usage Data</strong> - Data collected automatically from the use of the Service</p>
                    <p class="mb-4"><strong>Cookies</strong> - Small files stored on your device</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Information Collection and Use</h3>
                    <p class="mb-3">We collect several different types of information for various purposes to provide and improve our Service to you.</p>

                    <h4 style="font-size: 16px; font-weight: 600; margin: 20px 0 12px;">Personal Data</h4>
                    <p class="mb-3">While using our Service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you. This may include:</p>
                    <ul style="margin-left: 20px; margin-bottom: 20px;">
                        <li>Email address</li>
                        <li>First name and last name</li>
                        <li>Cookies and Usage Data</li>
                    </ul>

                    <h4 style="font-size: 16px; font-weight: 600; margin: 20px 0 12px;">Tracking & Cookies Data</h4>
                    <p class="mb-3">We use cookies and similar tracking technologies to track activity on our Service. Cookies are files with a small amount of data which may include an anonymous unique identifier.</p>
                    <p class="mb-3">You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Use of Data</h3>
                    <p class="mb-3">SNAPI LLC uses the collected data for various purposes:</p>
                    <ul style="margin-left: 20px; margin-bottom: 20px;">
                        <li>To provide and maintain our Service</li>
                        <li>To notify you about changes to our Service</li>
                        <li>To provide customer support</li>
                        <li>To gather analysis to improve our Service</li>
                        <li>To monitor the usage of our Service</li>
                        <li>To detect, prevent and address technical issues</li>
                    </ul>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Security of Data</h3>
                    <p class="mb-3">The security of your data is important to us but remember that no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Data, we cannot guarantee its absolute security.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Payments</h3>
                    <p class="mb-3">We may provide paid products and/or services within the Service. In that case, we use third-party services for payment processing (e.g. payment processors).</p>
                    <p class="mb-3">We will not store or collect your payment card details. That information is provided directly to our third-party payment processors whose use of your personal information is governed by their Privacy Policy.</p>
                    <p class="mb-3">The payment processor we work with is <strong>Stripe</strong>. Their Privacy Policy can be viewed at <a href="https://stripe.com/us/privacy" target="_blank">https://stripe.com/us/privacy</a></p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Changes to This Privacy Policy</h3>
                    <p class="mb-3">We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Contact Us</h3>
                    <p>If you have any questions about this Privacy Policy, please <a href="{{ route('pages.contact') }}">contact us</a>.</p>
                </div>
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
                        aisupport.bot
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
                <p>&copy; {{ date('Y') }} aisupport.bot. All rights reserved.</p>
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
</script>
@endpush
