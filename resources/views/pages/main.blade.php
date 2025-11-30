@extends('layouts.landing')

@section('content')
    <!-- Header -->
    <header class="landing-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">
                    <a href="/" class="logo-link">
                        <span class="logo-text">Bot<span>Pal</span></span>
                    </a>
                </div>
                <div class="col-6">
                    <nav class="landing-nav justify-content-end">
                        <a href="{{ route('pricing') }}" class="landing-nav-link">Pricing</a>
                        <a href="{{ route('login') }}" class="landing-nav-link">Log in</a>
                        <a href="{{ route('register') }}" class="landing-btn-primary">Start for free</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-badge animate-fade-in">
                <span class="hero-badge-dot"></span>
                <span>New: GPT-4 Integration Available</span>
            </div>

            <h1 class="hero-title animate-fade-in delay-1">
                Build <span class="highlight">AI Chatbots</span><br>
                That Convert
            </h1>

            <p class="hero-subtitle animate-fade-in delay-2">
                Create intelligent, personalized chatbots for customer support in minutes.
                No coding required. Powered by the latest AI technology.
            </p>

            <div class="hero-cta animate-fade-in delay-3">
                <a href="{{ route('register') }}" class="landing-btn-primary">Get Started Free</a>
                <a href="{{ route('pricing') }}" class="landing-btn-secondary">View Pricing</a>
            </div>

            <div class="product-preview animate-fade-in delay-4">
                <img src="/images/homeanima.gif" alt="BotPal Dashboard Preview" class="product-preview-image">
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">10<span class="accent">K+</span></div>
                    <div class="stat-label">Active Chatbots</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5<span class="accent">M+</span></div>
                    <div class="stat-label">Messages Handled</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98<span class="accent">%</span></div>
                    <div class="stat-label">Customer Satisfaction</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24<span class="accent">/7</span></div>
                    <div class="stat-label">Always Available</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Everything You Need</h2>
                <p class="section-subtitle">Powerful features to create, customize, and deploy intelligent chatbots that understand your business.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <h3 class="feature-title">Knowledge Base</h3>
                    <p class="feature-description">Train your bot with FAQs, documents, and PDFs. Your chatbot learns from your content to provide accurate, contextual responses.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <h3 class="feature-title">AI Model Selection</h3>
                    <p class="feature-description">Choose from multiple OpenAI models to find the perfect balance of speed, accuracy, and cost for your needs.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-palette-fill"></i>
                    </div>
                    <h3 class="feature-title">Full Customization</h3>
                    <p class="feature-description">Match your brand perfectly with custom colors, fonts, avatars, and messaging styles. Make it truly yours.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-code-slash"></i>
                    </div>
                    <h3 class="feature-title">Easy Integration</h3>
                    <p class="feature-description">Add your chatbot to any website with a simple embed code. Works with WordPress, Shopify, and custom sites.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <h3 class="feature-title">Conversation Inbox</h3>
                    <p class="feature-description">Monitor all conversations in real-time. Review, analyze, and improve your bot's responses from one dashboard.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <h3 class="feature-title">Analytics & Insights</h3>
                    <p class="feature-description">Track performance with detailed analytics. Understand user behavior and optimize your chatbot's effectiveness.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">Get your AI chatbot up and running in three simple steps.</p>
            </div>

            <div class="steps-container">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Choose & Create</h3>
                    <p class="step-description">Sign up and create your first chatbot. Select from our library of characters or upload your own avatar.</p>
                </div>

                <div class="step-item">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Train & Customize</h3>
                    <p class="step-description">Add your knowledge base, customize the appearance, and configure your bot's personality and responses.</p>
                </div>

                <div class="step-item">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Deploy & Engage</h3>
                    <p class="step-description">Embed your chatbot on your website and start engaging with visitors 24/7 with intelligent conversations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Use Cases Section -->
    <section class="use-cases">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Built for Every Business</h2>
                <p class="section-subtitle">From startups to enterprises, BotPal adapts to your unique needs.</p>
            </div>

            <div class="use-case-content">
                <div class="use-case-text">
                    <h3>Customer Support</h3>
                    <p>Reduce support ticket volume by up to 70% with AI-powered responses that handle common questions instantly, freeing your team for complex issues.</p>
                    <ul class="use-case-list">
                        <li>Instant responses 24/7</li>
                        <li>Handles FAQs automatically</li>
                        <li>Seamless handoff to humans</li>
                        <li>Multi-language support</li>
                    </ul>
                </div>
                <div class="use-case-image">
                    <img src="/images/web1.png" alt="Customer Support Chatbot">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Ready to Transform Your Customer Experience?</h2>
            <p class="cta-subtitle">Join thousands of businesses using BotPal to deliver exceptional support. Start free, no credit card required.</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="landing-btn-primary">Get Started Free</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo-text">Bot<span>Pal</span></div>
                    <p>AI-powered chatbots that help businesses deliver exceptional customer experiences around the clock.</p>
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
                    <a href="#" aria-label="GitHub"><i class="bi bi-github"></i></a>
                </div>
            </div>
        </div>
    </footer>
@endsection
