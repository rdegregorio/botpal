@extends('layouts.landing')

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
                        <a href="#features" class="navbar-link">Features</a>
                        <a href="#how-it-works" class="navbar-link">How it works</a>
                        <a href="#pricing" class="navbar-link">Pricing</a>
                        <a href="#faq" class="navbar-link">FAQ</a>
                    </div>
                    <div class="navbar-cta">
                        @auth
                            <a href="{{ route('account.index') }}" class="navbar-link">Account</a>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-inner">
                <h1 class="hero-title">
                    The easiest way to build<br>AI chatbots
                </h1>
                <p class="hero-description">
                    Create custom AI chatbots trained on your content in minutes. Reduce support tickets and delight customers with instant, accurate answers 24/7.
                </p>
                <div class="hero-cta">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-large">Get started free</a>
                    <a href="#how-it-works" class="btn btn-secondary btn-large">See how it works</a>
                </div>
                <p class="hero-note">No credit card required</p>
            </div>

            <!-- Hero Demo - Animated Chat -->
            <div class="hero-demo-wrapper">
                <div class="hero-demo">
                    <div class="hero-demo-header">
                        <span class="hero-demo-dot red"></span>
                        <span class="hero-demo-dot yellow"></span>
                        <span class="hero-demo-dot green"></span>
                    </div>
                    <div class="hero-demo-content" id="chat-demo">
                        <!-- Messages will be animated here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Logos Section -->
    <section class="logos-section">
        <div class="container">
            <p class="logos-label">Trusted by 1,000+ businesses</p>
            <div class="logos-grid">
                <span class="logo-item">Startups</span>
                <span class="logo-item">E-commerce</span>
                <span class="logo-item">SaaS</span>
                <span class="logo-item">Agencies</span>
                <span class="logo-item">Enterprise</span>
            </div>
        </div>
    </section>

    <!-- Feature Section 1: Train on Your Content -->
    <section class="feature-section" id="features">
        <div class="container">
            <div class="feature-row">
                <div class="feature-content">
                    <span class="feature-label">Train on your content</span>
                    <h2 class="feature-title">Build chatbots that actually understand your business</h2>
                    <p class="feature-description">
                        Upload your FAQs, documents, PDFs, or website content. Your chatbot learns everything about your business and speaks your language.
                    </p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check2"></i> Import from websites, PDFs, docs</li>
                        <li><i class="bi bi-check2"></i> Train on your FAQ database</li>
                        <li><i class="bi bi-check2"></i> Automatic content syncing</li>
                        <li><i class="bi bi-check2"></i> Multi-language support</li>
                    </ul>
                </div>
                <div class="feature-visual">
                    <div class="feature-card-grid">
                        <div class="mini-card">
                            <div class="mini-card-header">
                                <div class="mini-card-icon"><i class="bi bi-file-earmark-text"></i></div>
                                <h4>Documents</h4>
                            </div>
                            <p>Upload PDFs, Word docs, and text files</p>
                        </div>
                        <div class="mini-card">
                            <div class="mini-card-header">
                                <div class="mini-card-icon"><i class="bi bi-globe"></i></div>
                                <h4>Websites</h4>
                            </div>
                            <p>Crawl and import your entire website</p>
                        </div>
                        <div class="mini-card">
                            <div class="mini-card-header">
                                <div class="mini-card-icon"><i class="bi bi-question-circle"></i></div>
                                <h4>FAQs</h4>
                            </div>
                            <p>Import existing FAQ databases</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Section 2: Customization -->
    <section class="feature-section alt">
        <div class="container">
            <div class="feature-row reverse">
                <div class="feature-content">
                    <span class="feature-label">Full customization</span>
                    <h2 class="feature-title">Make it yours with complete styling control</h2>
                    <p class="feature-description">
                        Match your brand perfectly with custom colors, fonts, avatars, and chat styles. Your chatbot will feel native to your website.
                    </p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check2"></i> Custom colors and branding</li>
                        <li><i class="bi bi-check2"></i> Personalized welcome messages</li>
                        <li><i class="bi bi-check2"></i> Custom bot personality</li>
                        <li><i class="bi bi-check2"></i> White-label options</li>
                    </ul>
                </div>
                <div class="feature-visual">
                    <div class="feature-card-grid">
                        <div class="mini-card">
                            <div class="mini-card-header">
                                <div class="mini-card-icon"><i class="bi bi-palette"></i></div>
                                <h4>Themes</h4>
                            </div>
                            <p>Light, dark, or custom color schemes</p>
                        </div>
                        <div class="mini-card">
                            <div class="mini-card-header">
                                <div class="mini-card-icon"><i class="bi bi-person-circle"></i></div>
                                <h4>Avatar</h4>
                            </div>
                            <p>Upload your own bot avatar</p>
                        </div>
                        <div class="mini-card">
                            <div class="mini-card-header">
                                <div class="mini-card-icon"><i class="bi bi-chat-square-text"></i></div>
                                <h4>Tone</h4>
                            </div>
                            <p>Set your bot's personality and tone</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Section 3: Analytics -->
    <section class="feature-section">
        <div class="container">
            <div class="feature-row">
                <div class="feature-content">
                    <span class="feature-label">Analytics & insights</span>
                    <h2 class="feature-title">Understand what your customers need</h2>
                    <p class="feature-description">
                        Track conversations, identify trending questions, and continuously improve your chatbot's responses with actionable insights.
                    </p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check2"></i> Real-time conversation monitoring</li>
                        <li><i class="bi bi-check2"></i> Popular questions dashboard</li>
                        <li><i class="bi bi-check2"></i> Resolution rate tracking</li>
                        <li><i class="bi bi-check2"></i> Customer satisfaction scores</li>
                    </ul>
                </div>
                <div class="feature-visual">
                    <div class="feature-card-grid">
                        <div class="mini-card">
                            <div class="mini-card-header">
                                <div class="mini-card-icon"><i class="bi bi-graph-up"></i></div>
                                <h4>Metrics</h4>
                            </div>
                            <p>Track resolution rates and response times</p>
                        </div>
                        <div class="mini-card">
                            <div class="mini-card-header">
                                <div class="mini-card-icon"><i class="bi bi-inbox"></i></div>
                                <h4>Inbox</h4>
                            </div>
                            <p>Review all conversations in one place</p>
                        </div>
                        <div class="mini-card">
                            <div class="mini-card-header">
                                <div class="mini-card-icon"><i class="bi bi-lightning"></i></div>
                                <h4>Insights</h4>
                            </div>
                            <p>AI-powered suggestions to improve</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Integrations Section -->
    <section class="feature-section alt">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Integrations</span>
                <h2 class="section-title">Works with your favorite platforms</h2>
                <p class="section-description">Easily integrate BotPal with any website. Just paste one line of code.</p>
            </div>
            <div class="integrations-grid">
                <div class="integration-item">
                    <div class="integration-icon">
                        <svg viewBox="0 0 109.5 124.5" width="40" height="40">
                            <path fill="#95BF47" d="M74.7,14.8c0,0-1.4,0.4-3.7,1.1c-0.4-1.3-1-2.8-1.8-4.4c-2.6-5-6.5-7.7-11.1-7.7c0,0,0,0,0,0 c-0.3,0-0.6,0-1,0.1c-0.1-0.2-0.3-0.3-0.4-0.5c-2-2.2-4.6-3.2-7.7-3.2c-6,0.2-12,4.5-16.8,12.2c-3.4,5.4-6,12.2-6.7,17.5 c-6.9,2.1-11.7,3.6-11.8,3.7c-3.5,1.1-3.6,1.2-4,4.5C9.4,40.4,0,111.9,0,111.9l75.6,13.1V14.6C75.2,14.7,74.9,14.7,74.7,14.8z"/>
                            <path fill="#5E8E3E" d="M74.7,14.8c0,0-1.4,0.4-3.7,1.1c-0.4-1.3-1-2.8-1.8-4.4c-2.6-5-6.5-7.7-11.1-7.7c0,0,0,0,0,0 c-0.3,0-0.6,0-1,0.1c-0.1-0.2-0.3-0.3-0.4-0.5c-2-2.2-4.6-3.2-7.7-3.2c-6,0.2-12,4.5-16.8,12.2c-3.4,5.4-6,12.2-6.7,17.5"/>
                            <path fill="#FFF" d="M57.6,3.9c0.3,0,0.6,0,0.9,0c4.3,0,7.5,2.6,10,7.3c1.8,3.6,3,7.6,3.6,10.6c-5.5,1.7-11.4,3.5-17.5,5.4 c1.7-6.5,4.9-12.9,8.8-17.6C54.9,7.8,56.5,5.5,57.6,3.9z M49.1,0.3c0.7,0,1.4,0.2,2.1,0.5c-4.1,1.9-8.5,6.9-11.6,14.8 c-2.5,0.8-4.9,1.5-7.2,2.2c2-6.3,6.7-14.6,13.3-17.2C46.7,0.4,47.8,0.3,49.1,0.3z"/>
                            <path fill="#F5F5F5" d="M72.8,15.5c-0.4,0.1-0.7,0.2-1.1,0.3c-0.6-3.2-1.9-7.3-3.8-11.1c3.8,0.5,6.4,4.9,4.9,10.8z"/>
                            <path fill="#95BF47" d="M75.6,125l33.9-7.3c0,0-14.5-93.9-14.6-94.4c-0.1-0.5-0.5-0.8-0.9-0.8c-0.4,0-8.2-0.2-8.2-0.2 s-4.3-4-6.1-5.6V125z"/>
                        </svg>
                    </div>
                    <span>Shopify</span>
                </div>
                <div class="integration-item">
                    <div class="integration-icon">
                        <svg viewBox="0 0 122.5 122.5" width="40" height="40">
                            <path fill="#21759B" d="M8.7,61.3c0,19.1,11.1,35.6,27.2,43.4L12.3,38.4C10,45.4,8.7,53.2,8.7,61.3z M96.6,58.6 c0-6-2.1-10.1-4-13.3c-2.4-4-4.7-7.3-4.7-11.3c0-4.4,3.4-8.5,8.1-8.5c0.2,0,0.4,0,0.6,0c-8.6-7.9-20-12.7-32.5-12.7 c-16.8,0-31.6,8.6-40.2,21.7c1.1,0,2.2,0.1,3.1,0.1c5,0,12.8-0.6,12.8-0.6c2.6-0.2,2.9,3.7,0.3,4c0,0-2.6,0.3-5.5,0.5l17.5,52 l10.5-31.5l-7.5-20.5c-2.6-0.2-5-0.5-5-0.5c-2.6-0.2-2.3-4.1,0.3-4c0,0,8,0.6,12.6,0.6c5,0,12.8-0.6,12.8-0.6 c2.6-0.2,2.9,3.7,0.3,4c0,0-2.6,0.3-5.5,0.5l17.4,51.7l4.8-16C95.3,67.1,96.6,62.3,96.6,58.6z M61.8,66l-14.4,41.9 c4.3,1.3,8.9,2,13.7,2c5.6,0,11-1,16-2.7c-0.1-0.2-0.3-0.5-0.4-0.7L61.8,66z M103.3,35.5c0.2,1.6,0.4,3.3,0.4,5.2 c0,5.1-1,10.9-3.9,18.1l-15.8,45.6c15.4-9,25.7-25.7,25.7-44.9C109.7,50,107.4,41.9,103.3,35.5z"/>
                            <path fill="#21759B" d="M61.3,0C27.4,0,0,27.4,0,61.3s27.4,61.3,61.3,61.3s61.3-27.4,61.3-61.3S95.1,0,61.3,0z M61.3,119.4 c-32.1,0-58.2-26.1-58.2-58.2s26.1-58.2,58.2-58.2s58.2,26.1,58.2,58.2S93.4,119.4,61.3,119.4z"/>
                        </svg>
                    </div>
                    <span>WordPress</span>
                </div>
                <div class="integration-item">
                    <div class="integration-icon">
                        <svg viewBox="0 0 100 100" width="40" height="40">
                            <path fill="#F26322" d="M50,0C22.4,0,0,22.4,0,50s22.4,50,50,50s50-22.4,50-50S77.6,0,50,0z M78.8,72.6c-0.8,1.9-2.3,3.4-4.2,4.2 c-7.2,3-30-0.2-30-0.2s-22.8,3.2-30,0.2c-1.9-0.8-3.4-2.3-4.2-4.2c-3-7.2,0.2-30,0.2-30s-3.2-22.8-0.2-30c0.8-1.9,2.3-3.4,4.2-4.2 c7.2-3,30,0.2,30,0.2s22.8-3.2,30-0.2c1.9,0.8,3.4,2.3,4.2,4.2c3,7.2-0.2,30-0.2,30S81.8,65.4,78.8,72.6z"/>
                            <path fill="#F26322" d="M50,22.5L35.5,50L50,77.5L64.5,50L50,22.5z"/>
                        </svg>
                    </div>
                    <span>Magento</span>
                </div>
                <div class="integration-item">
                    <div class="integration-icon">
                        <svg viewBox="0 0 100 100" width="40" height="40">
                            <rect fill="#0C6EFC" width="100" height="100" rx="10"/>
                            <path fill="#FFF" d="M25,30h50v10H25V30z M25,45h50v10H25V45z M25,60h30v10H25V60z"/>
                        </svg>
                    </div>
                    <span>Square</span>
                </div>
                <div class="integration-item">
                    <div class="integration-icon">
                        <svg viewBox="0 0 100 100" width="40" height="40">
                            <rect fill="#0C6EFC" width="100" height="100" rx="10"/>
                            <path fill="#FFF" d="M20,80l15-30l15,15l15-25l15,40H20z"/>
                            <circle fill="#FFF" cx="35" cy="35" r="8"/>
                        </svg>
                    </div>
                    <span>Wix</span>
                </div>
                <div class="integration-item">
                    <div class="integration-icon">
                        <svg viewBox="0 0 100 100" width="40" height="40">
                            <rect fill="#333" width="100" height="100" rx="10"/>
                            <text x="50" y="60" text-anchor="middle" fill="#FFF" font-size="24" font-weight="bold">&lt;/&gt;</text>
                        </svg>
                    </div>
                    <span>Any Website</span>
                </div>
            </div>
            <p style="text-align: center; color: var(--text-muted); margin-top: 32px;">Just paste a single line of code to get started</p>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <span class="section-label">How it works</span>
                <h2 class="section-title">Go live in 5 minutes</h2>
                <p class="section-description">Three simple steps to transform your customer support.</p>
            </div>
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Add your content</h3>
                    <p class="step-description">Upload your FAQs, help docs, or paste your website URL. Our AI learns from your content instantly.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Customize & test</h3>
                    <p class="step-description">Style your chatbot to match your brand. Test it out and refine responses until it's perfect.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Deploy & relax</h3>
                    <p class="step-description">Add one line of code to your site. Your AI assistant is now live, handling support 24/7.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Testimonials</span>
                <h2 class="section-title">Loved by support teams</h2>
                <p class="section-description">See what our customers have to say about BotPal.</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p class="testimonial-text">"BotPal reduced our support tickets by 65% in the first month. The AI actually understands our product and gives accurate answers."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">SK</div>
                        <div class="testimonial-info">
                            <h4>Sarah K.</h4>
                            <p>Head of Support, TechFlow</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"Setup took 10 minutes. Now our customers get instant answers at 3am instead of waiting for business hours. Game changer."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">MR</div>
                        <div class="testimonial-info">
                            <h4>Mike R.</h4>
                            <p>Founder, ShopEasy</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"The customization options are incredible. Our chatbot looks and feels like it was built in-house. Customers love it."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">JL</div>
                        <div class="testimonial-info">
                            <h4>Jennifer L.</h4>
                            <p>CTO, CloudBase</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section" id="pricing">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Pricing</span>
                <h2 class="section-title">Simple, transparent pricing</h2>
                <p class="section-description">Start free. Upgrade when you're ready. Powered by the latest GPT models.</p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <h3 class="pricing-name">Free</h3>
                    <p class="pricing-description">Perfect for trying out BotPal</p>
                    <div class="pricing-price">
                        <span class="pricing-amount">$0</span>
                        <span class="pricing-period">/month</span>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2"></i> 1 chatbot</li>
                        <li><i class="bi bi-check2"></i> 100 messages/month</li>
                        <li><i class="bi bi-check2"></i> Basic customization</li>
                        <li><i class="bi bi-check2"></i> Email support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Get started</a>
                </div>
                <div class="pricing-card featured">
                    <span class="pricing-badge">Popular</span>
                    <h3 class="pricing-name">Pro</h3>
                    <p class="pricing-description">For growing businesses</p>
                    <div class="pricing-price">
                        <span class="pricing-amount">$29</span>
                        <span class="pricing-period">/month</span>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2"></i> Unlimited chatbots</li>
                        <li><i class="bi bi-check2"></i> 5,000 messages/month</li>
                        <li><i class="bi bi-check2"></i> Full customization</li>
                        <li><i class="bi bi-check2"></i> GPT-4 access</li>
                        <li><i class="bi bi-check2"></i> Analytics dashboard</li>
                        <li><i class="bi bi-check2"></i> Priority support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary">Start free trial</a>
                </div>
                <div class="pricing-card">
                    <h3 class="pricing-name">Enterprise</h3>
                    <p class="pricing-description">For large organizations</p>
                    <div class="pricing-price">
                        <span class="pricing-amount">Custom</span>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2"></i> Everything in Pro</li>
                        <li><i class="bi bi-check2"></i> Unlimited messages</li>
                        <li><i class="bi bi-check2"></i> Custom integrations</li>
                        <li><i class="bi bi-check2"></i> SLA guarantee</li>
                        <li><i class="bi bi-check2"></i> Dedicated support</li>
                        <li><i class="bi bi-check2"></i> On-premise option</li>
                    </ul>
                    <a href="{{ route('pages.contact') }}" class="btn btn-secondary">Contact sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section" id="faq">
        <div class="container">
            <div class="section-header">
                <span class="section-label">FAQ</span>
                <h2 class="section-title">Questions? We've got answers.</h2>
            </div>
            <div class="faq-grid">
                <div class="faq-item open">
                    <div class="faq-question">
                        How does BotPal learn from my content?
                        <i class="bi bi-plus-lg faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        BotPal uses advanced AI to analyze your uploaded documents, FAQs, and website content. It understands context and meaning, not just keywords, allowing it to provide accurate, relevant answers to customer questions.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        Can I customize the chatbot's appearance?
                        <i class="bi bi-plus-lg faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        Yes! You can fully customize colors, fonts, avatar, chat bubble styles, and more to match your brand. The chatbot will look and feel native to your website.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        What AI models does BotPal use?
                        <i class="bi bi-plus-lg faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        BotPal supports all the latest GPT models including GPT-4, GPT-4 Turbo, and GPT-3.5. Pro and Enterprise plans get access to the most advanced models for the best responses.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        What happens if the AI can't answer a question?
                        <i class="bi bi-plus-lg faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        You can configure fallback responses and set up human handoff. When the AI is unsure, it can collect the customer's contact info or redirect them to your support team.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        How long does setup take?
                        <i class="bi bi-plus-lg faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        Most users are up and running in under 10 minutes. Simply upload your content, customize the appearance, copy the embed code, and you're live.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        Is my data secure?
                        <i class="bi bi-plus-lg faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        Absolutely. We use enterprise-grade encryption for all data. Your content and customer conversations are stored securely and never shared with third parties.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to get started?</h2>
                <p class="cta-description">Join 1,000+ businesses using BotPal to deliver instant, intelligent customer support.</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-large">Get started free</a>
                    <a href="{{ route('pages.contact') }}" class="btn btn-secondary btn-large">Contact sales</a>
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
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="#faq">FAQ</a></li>
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
                <div class="footer-social">
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                    <a href="#"><i class="bi bi-github"></i></a>
                </div>
            </div>
        </div>
    </footer>
@endsection

@push('scripts')
<script>
// Mobile menu toggle
const navbarToggle = document.getElementById('navbarToggle');
const navbarMenu = document.getElementById('navbarMenu');

navbarToggle.addEventListener('click', function() {
    navbarMenu.classList.toggle('active');
    const icon = this.querySelector('i');
    icon.classList.toggle('bi-list');
    icon.classList.toggle('bi-x-lg');
});

// Close mobile menu when clicking a link
document.querySelectorAll('.navbar-link').forEach(link => {
    link.addEventListener('click', () => {
        navbarMenu.classList.remove('active');
        const icon = navbarToggle.querySelector('i');
        icon.classList.add('bi-list');
        icon.classList.remove('bi-x-lg');
    });
});

// FAQ Toggle
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        const item = question.parentElement;
        item.classList.toggle('open');
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Animated chat demo
const chatMessages = [
    { type: 'bot', text: "Hi! I'm your AI assistant. How can I help you today?" },
    { type: 'user', text: "How do I reset my password?" },
    { type: 'bot', text: "To reset your password, go to Settings > Security > Change Password. You'll receive a confirmation email within 2 minutes." },
    { type: 'user', text: "That was fast! Thanks!" },
    { type: 'bot', text: "You're welcome! Is there anything else I can help you with?" }
];

const chatDemo = document.getElementById('chat-demo');
let messageIndex = 0;

function typeMessage(element, text, callback) {
    let charIndex = 0;
    const interval = setInterval(() => {
        element.textContent = text.substring(0, charIndex + 1);
        charIndex++;
        if (charIndex >= text.length) {
            clearInterval(interval);
            if (callback) setTimeout(callback, 800);
        }
    }, 30);
}

function addMessage() {
    if (messageIndex >= chatMessages.length) {
        // Reset and start over after a pause
        setTimeout(() => {
            chatDemo.innerHTML = '';
            messageIndex = 0;
            addMessage();
        }, 3000);
        return;
    }

    const msg = chatMessages[messageIndex];
    const messageDiv = document.createElement('div');
    messageDiv.className = 'chat-message' + (msg.type === 'user' ? ' user' : '');

    const avatarDiv = document.createElement('div');
    avatarDiv.className = 'chat-avatar' + (msg.type === 'user' ? ' user' : '');
    avatarDiv.innerHTML = msg.type === 'user' ? '<i class="bi bi-person"></i>' : '<i class="bi bi-robot"></i>';

    const bubbleDiv = document.createElement('div');
    bubbleDiv.className = 'chat-bubble';

    messageDiv.appendChild(avatarDiv);
    messageDiv.appendChild(bubbleDiv);
    chatDemo.appendChild(messageDiv);

    // Scroll to bottom
    chatDemo.scrollTop = chatDemo.scrollHeight;

    typeMessage(bubbleDiv, msg.text, () => {
        messageIndex++;
        addMessage();
    });
}

// Start the animation after page loads
setTimeout(addMessage, 1000);
</script>
@endpush
