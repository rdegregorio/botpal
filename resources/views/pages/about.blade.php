@extends('layouts.landing')

@section('title', 'About Us - aisupport.bot')

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
                <h1 style="font-size: 32px; font-weight: 600; margin-bottom: 8px;">About aisupport.bot</h1>
                <p style="color: #6b6b6b; margin-bottom: 32px;">Empowering businesses with intelligent AI chatbots</p>

                <div style="color: #444; font-size: 15px; line-height: 1.7;">
                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Our Mission</h3>
                    <p class="mb-4">At aisupport.bot, we believe every business deserves access to cutting-edge AI technology to enhance their customer support. Our mission is to make it incredibly simple for businesses of all sizes to create intelligent, personalized chatbots that actually understand their customers.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">What We Do</h3>
                    <p class="mb-3">aisupport.bot is an AI-powered chatbot platform that helps businesses:</p>
                    <ul style="margin-left: 20px; margin-bottom: 20px;">
                        <li><strong>Reduce support tickets</strong> - Let AI handle common questions 24/7</li>
                        <li><strong>Improve response times</strong> - Instant answers instead of hours or days</li>
                        <li><strong>Scale efficiently</strong> - Handle thousands of conversations simultaneously</li>
                        <li><strong>Delight customers</strong> - Provide accurate, helpful responses every time</li>
                    </ul>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Our Technology</h3>
                    <p class="mb-3">Powered by the latest GPT models, aisupport.bot offers:</p>
                    <ul style="margin-left: 20px; margin-bottom: 20px;">
                        <li><strong>Natural language understanding</strong> - Your chatbot actually understands context and nuance</li>
                        <li><strong>Custom training</strong> - Train on your specific content, FAQs, and documentation</li>
                        <li><strong>Multi-platform support</strong> - Works with Shopify, WordPress, Magento, Wix, and any website</li>
                        <li><strong>Full customization</strong> - Match your brand with custom colors, fonts, and avatars</li>
                    </ul>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Our Story</h3>
                    <p class="mb-4">aisupport.bot was founded by a team passionate about making AI accessible to everyone. After seeing countless small businesses struggle with overwhelming customer support demands, we set out to create a solution that's both powerful and easy to use. Today, aisupport.bot serves thousands of businesses worldwide, helping them provide exceptional customer experiences.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Why Choose aisupport.bot?</h3>
                    <ul style="margin-left: 20px; margin-bottom: 20px;">
                        <li><strong>Easy setup</strong> - Go live in under 10 minutes</li>
                        <li><strong>No coding required</strong> - Just paste one line of code</li>
                        <li><strong>Affordable pricing</strong> - Plans for every budget, starting free</li>
                        <li><strong>Reliable support</strong> - We're here to help you succeed</li>
                    </ul>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Get In Touch</h3>
                    <p>Have questions or want to learn more? We'd love to hear from you. <a href="{{ route('pages.contact') }}">Contact us</a> today.</p>
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
