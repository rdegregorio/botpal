@extends('layouts.landing')

@section('title', 'Terms and Conditions - BotPal')

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
                <h1 style="font-size: 32px; font-weight: 600; margin-bottom: 8px;">Terms and Conditions</h1>
                <p style="color: #6b6b6b; margin-bottom: 32px;">Last updated: August 21, 2023</p>

                <div style="color: #444; font-size: 15px; line-height: 1.7;">
                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Introduction</h3>
                    <p class="mb-4">Welcome to BotPal, a service provided by Snapi LLC, registered in Miami, Florida, USA. By creating an account and using BotPal, you are expressly consenting to these Terms. If you disagree with any part of these Terms, please refrain from using our platform or associated services.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Purpose of the Service</h3>
                    <p class="mb-4">BotPal is a live chat AI platform designed for e-commerce businesses and individuals. Our service allows users to input their FAQs, text, or PDF documents to create customized AI chatbots. We utilize sophisticated AI capabilities to deliver prompt and intelligent responses, enhancing user engagement on your platform.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Using Our Service</h3>
                    <p class="mb-3">To use BotPal, users need to register an account. We offer various subscription plans to suit your needs. Features include:</p>
                    <ul style="margin-left: 20px; margin-bottom: 20px;">
                        <li>Input FAQs, texts, or PDFs to create your chatbot</li>
                        <li>Select from various chatbot characters to personalize your experience</li>
                        <li>Review and oversee your bot's responses</li>
                    </ul>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Subscription Model</h3>
                    <p class="mb-3">The subscription agreement is based on a monthly recurring model that automatically renews unless terminated.</p>
                    <p class="mb-2"><strong>Upgrades:</strong> If you upgrade during an active billing cycle, the differential amount will be charged at the beginning of the next billing cycle.</p>
                    <p class="mb-2"><strong>Downgrades:</strong> If you downgrade, the difference will be credited to your account for future use.</p>
                    <p class="mb-4"><strong>Cancellation:</strong> You can terminate your subscription at any time. Access continues until the end of the current billing period. No partial refunds are provided.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Usage Restrictions</h3>
                    <p class="mb-3">By using our service, you agree not to engage in:</p>
                    <ul style="margin-left: 20px; margin-bottom: 20px;">
                        <li>Illegal activity</li>
                        <li>Generation of hateful, harassing, or violent content</li>
                        <li>Generation of malware</li>
                        <li>Fraudulent or deceptive activity</li>
                        <li>Adult content</li>
                        <li>Political campaigning or lobbying</li>
                        <li>Activity that violates people's privacy</li>
                    </ul>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Intellectual Property Rights</h3>
                    <p class="mb-3"><strong>Ownership:</strong> All rights to the service, software, and trademarks are owned by Snapi LLC.</p>
                    <p class="mb-4"><strong>License:</strong> Users are granted a limited, non-exclusive, non-transferable license to use BotPal for its intended purpose.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Service Availability</h3>
                    <p class="mb-4">While we aim to maintain 99.9% uptime, we cannot guarantee continuous, uninterrupted access. Users will be informed about planned maintenance via our official channels.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Limitation of Liability</h3>
                    <p class="mb-4">TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, SNAPI LLC SHALL NOT BE LIABLE FOR ANY INDIRECT, PUNITIVE, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR EXEMPLARY DAMAGES ARISING OUT OF OR RELATING TO THE USE OF THE SERVICE.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Governing Law</h3>
                    <p class="mb-4">These terms and conditions are governed by the laws of the state of Florida, USA.</p>

                    <hr style="border-color: #e8e8e8; margin: 24px 0;">

                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Contact Us</h3>
                    <p>For any concerns regarding these Terms, please <a href="{{ route('pages.contact') }}">contact us</a>.</p>
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
</script>
@endpush
