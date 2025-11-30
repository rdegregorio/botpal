@extends('layouts.landing')

@section('content')
    <!-- Floating Pill Navbar -->
    <header class="landing-header">
        <nav class="navbar-pill">
            <a href="/" class="navbar-logo">
                <span class="navbar-logo-icon"><i class="bi bi-chat-dots-fill"></i></span>
                BotPal
            </a>
            <div class="navbar-links">
                <a href="{{ route('pricing') }}" class="navbar-link">Pricing</a>
                <a href="{{ route('login') }}" class="navbar-link">Log in</a>
                <a href="{{ route('register') }}" class="btn-primary-pill">Join for free</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-icon">
            <i class="bi bi-robot"></i>
        </div>

        <h1 class="hero-title">
            Discover the power of<br>AI customer support.
        </h1>

        <p class="hero-subtitle">
            Create intelligent chatbots trained on your content. Featuring easy setup,
            full customization & powerful analytics — New features weekly.
        </p>

        <div class="hero-cta">
            <a href="{{ route('register') }}" class="btn-primary-pill">Join for free</a>
            <a href="{{ route('pricing') }}" class="btn-secondary-pill">
                See our plans <span class="arrow">&rarr;</span>
            </a>
        </div>
    </section>

    <!-- Trusted By Section -->
    <section class="trusted-section">
        <p class="trusted-label">Trusted by support teams at</p>
        <div class="trusted-logos">
            <span class="trusted-logo">Startups</span>
            <span class="trusted-logo">E-commerce</span>
            <span class="trusted-logo">SaaS</span>
            <span class="trusted-logo">Agencies</span>
            <span class="trusted-logo">Enterprises</span>
        </div>
    </section>

    <!-- Two Column Feature Section -->
    <section class="two-col-section">
        <div class="section-header">
            <h2 class="section-title">Explore entire customer<br>journeys with AI.</h2>
        </div>

        <div class="two-col-grid">
            <div class="two-col-card">
                <div class="two-col-card-image">
                    <div class="phone-mock">
                        <div class="phone-mock-inner">
                            <span class="phone-mock-text">Chat Preview</span>
                        </div>
                    </div>
                </div>
                <h3>Live Chat Preview</h3>
                <p>Experience your chatbot exactly as your customers will. Test responses, refine answers, and perfect the conversation flow.</p>
            </div>

            <div class="two-col-card">
                <div class="two-col-card-image">
                    <div class="dark-phone-mock">
                        <h4>Choose your AI model</h4>
                        <div class="dark-phone-option selected">
                            <i class="bi bi-cpu"></i> GPT-4
                        </div>
                        <div class="dark-phone-option">
                            <i class="bi bi-lightning"></i> GPT-3.5 Turbo
                        </div>
                    </div>
                </div>
                <h3>Model Selection</h3>
                <p>Choose the perfect AI model for your needs. Switch between GPT-4 and GPT-3.5 to balance power and cost.</p>
            </div>
        </div>
    </section>

    <!-- Feature Cards Section -->
    <section class="features-section">
        <div class="section-header">
            <h2 class="section-title">From setup to success.</h2>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-card-image">
                    <div class="feature-card-mock">
                        <i class="bi bi-check-lg"></i> Trained on your content
                    </div>
                </div>
                <h3>Knowledge Base</h3>
                <p>Upload FAQs, documents, and PDFs. Your chatbot learns from your content to provide accurate answers.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card-image">
                    <div class="feature-card-mock">
                        <i class="bi bi-bookmark"></i> Save to collections
                    </div>
                </div>
                <h3>Conversation Inbox</h3>
                <p>Review all conversations in one place. Analyze responses and continuously improve your bot.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card-image">
                    <div class="feature-card-mock">
                        <i class="bi bi-chat-square-text"></i> Add context notes
                    </div>
                </div>
                <h3>Easy Integration</h3>
                <p>Embed your chatbot anywhere with a simple code snippet. Works with any website or platform.</p>
            </div>
        </div>
    </section>

    <!-- Pricing Preview Section -->
    <section class="pricing-preview">
        <h2 class="section-title">Support like a Pro.</h2>
        <p class="section-subtitle" style="margin-bottom: 32px;">Get full access to all features from only $0.33 per day — Cancel anytime.</p>

        <div class="pricing-toggle">
            <button class="pricing-toggle-btn active">Monthly</button>
            <button class="pricing-toggle-btn">Yearly</button>
        </div>

        <p class="pricing-save"><span>Save 33%</span> on a yearly subscription</p>

        <div class="pricing-cards">
            <div class="pricing-card">
                <div class="pricing-card-header">
                    <div class="pricing-card-name">
                        <h3>Pro</h3>
                        <span class="pricing-badge">Popular</span>
                    </div>
                    <p class="pricing-card-desc">For individuals</p>
                    <div class="pricing-card-price">
                        <span class="amount">$10</span>
                        <span class="period">per month</span>
                    </div>
                </div>
                <a href="{{ route('register') }}" class="btn-primary-pill">Get started</a>
                <ul class="pricing-features">
                    <li><i class="bi bi-box"></i> Unlimited chatbots</li>
                    <li><i class="bi bi-file-text"></i> PDF & document training</li>
                    <li><i class="bi bi-eye"></i> Live preview</li>
                    <li><i class="bi bi-palette"></i> Full customization</li>
                    <li><i class="bi bi-code-slash"></i> Easy embed code</li>
                    <li><i class="bi bi-bar-chart"></i> Analytics dashboard</li>
                </ul>
            </div>

            <div class="pricing-card secondary">
                <div class="pricing-card-header">
                    <div class="pricing-card-name">
                        <h3>Team</h3>
                    </div>
                    <p class="pricing-card-desc">For teams & agencies</p>
                    <div class="pricing-card-price">
                        <span class="amount">$25</span>
                        <span class="period">per month</span>
                    </div>
                </div>
                <a href="{{ route('register') }}" class="btn-primary-pill">Get started</a>
                <ul class="pricing-features">
                    <li><i class="bi bi-check"></i> All Pro features</li>
                    <li><i class="bi bi-people"></i> Team collaboration</li>
                    <li><i class="bi bi-chat-dots"></i> Priority support</li>
                    <li><i class="bi bi-shield-check"></i> Advanced analytics</li>
                    <li><i class="bi bi-gear"></i> Custom branding</li>
                    <li><i class="bi bi-infinity"></i> Unlimited messages</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2 class="cta-title">Ready to get started?</h2>
        <p class="cta-subtitle">Join thousands of businesses using BotPal. Start free, no credit card required.</p>
        <a href="{{ route('register') }}" class="btn-primary-pill" style="padding: 14px 32px; font-size: 16px;">Join for free</a>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="/" class="footer-logo">
                    <span class="navbar-logo-icon"><i class="bi bi-chat-dots-fill"></i></span>
                    BotPal
                </a>
                <p>AI-powered chatbots that help businesses deliver exceptional customer support around the clock.</p>
            </div>

            <div class="footer-column">
                <h4>Product</h4>
                <ul>
                    <li><a href="{{ route('pricing') }}">Pricing</a></li>
                    <li><a href="{{ route('register') }}">Get Started</a></li>
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
                    <li><a href="{{ route('pages.privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('pages.terms') }}">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} BotPal. All rights reserved.</p>
            <div class="footer-social">
                <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </footer>
@endsection
