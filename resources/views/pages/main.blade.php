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
                <div class="navbar-right">
                    <div class="navbar-links">
                        <a href="#features" class="navbar-link">Features</a>
                        <a href="#how-it-works" class="navbar-link">How it works</a>
                        <a href="#pricing" class="navbar-link">Pricing</a>
                        <a href="#faq" class="navbar-link">FAQ</a>
                    </div>
                    <div class="navbar-cta">
                        <a href="{{ route('login') }}" class="navbar-link">Log in</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Start Free Trial</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        Now with GPT-4 Support
                    </div>
                    <h1 class="hero-title">
                        Automate Support.<br>
                        <span>Delight Customers.</span>
                    </h1>
                    <p class="hero-description">
                        Create an AI chatbot trained on your content in minutes. Reduce support tickets by 70% while delivering instant, accurate answers 24/7.
                    </p>
                    <div class="hero-cta">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-large">Start Free Trial</a>
                        <a href="#how-it-works" class="btn btn-secondary btn-large">See How It Works</a>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <i class="bi bi-check-circle-fill hero-stat-icon"></i>
                            <span class="hero-stat-text"><strong>No credit card</strong> required</span>
                        </div>
                        <div class="hero-stat">
                            <i class="bi bi-check-circle-fill hero-stat-icon"></i>
                            <span class="hero-stat-text"><strong>5-minute</strong> setup</span>
                        </div>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="hero-demo">
                        <div class="hero-demo-header">
                            <span class="hero-demo-dot red"></span>
                            <span class="hero-demo-dot yellow"></span>
                            <span class="hero-demo-dot green"></span>
                        </div>
                        <div class="hero-demo-content">
                            <div class="chat-message">
                                <div class="chat-avatar"><i class="bi bi-robot"></i></div>
                                <div class="chat-bubble">Hi! I'm your AI assistant. How can I help you today?</div>
                            </div>
                            <div class="chat-message user">
                                <div class="chat-avatar user"><i class="bi bi-person"></i></div>
                                <div class="chat-bubble">How do I reset my password?</div>
                            </div>
                            <div class="chat-message">
                                <div class="chat-avatar"><i class="bi bi-robot"></i></div>
                                <div class="chat-bubble">To reset your password, go to Settings > Security > Change Password. You'll receive a confirmation email within 2 minutes.</div>
                            </div>
                            <div class="chat-message user">
                                <div class="chat-avatar user"><i class="bi bi-person"></i></div>
                                <div class="chat-bubble">That was fast! Thanks!</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Logos Section -->
    <section class="logos-section">
        <div class="container">
            <p class="logos-label">Trusted by 1,000+ businesses worldwide</p>
            <div class="logos-grid">
                <span class="logo-item">Startups</span>
                <span class="logo-item">E-commerce</span>
                <span class="logo-item">SaaS</span>
                <span class="logo-item">Agencies</span>
                <span class="logo-item">Enterprise</span>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">70%</div>
                    <div class="stat-label">Fewer Support Tickets</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Always Available</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">&lt;3s</div>
                    <div class="stat-label">Response Time</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Features</span>
                <h2 class="section-title">Everything you need to automate support</h2>
                <p class="section-description">Powerful features that help you create, train, and deploy AI chatbots that actually understand your business.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-book"></i></div>
                    <h3 class="feature-title">Train on Your Content</h3>
                    <p class="feature-description">Upload FAQs, documents, PDFs, or website content. Your chatbot learns your business and speaks your language.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-cpu"></i></div>
                    <h3 class="feature-title">Powered by GPT-4</h3>
                    <p class="feature-description">Choose from multiple AI models including GPT-4 and GPT-3.5 to balance performance and cost for your needs.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-palette"></i></div>
                    <h3 class="feature-title">Full Customization</h3>
                    <p class="feature-description">Match your brand with custom colors, fonts, avatars, and chat styles. Make it feel native to your website.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-code-slash"></i></div>
                    <h3 class="feature-title">One-Line Integration</h3>
                    <p class="feature-description">Add your chatbot to any website with a single line of code. Works with WordPress, Shopify, and any platform.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-inbox"></i></div>
                    <h3 class="feature-title">Conversation Inbox</h3>
                    <p class="feature-description">Monitor all conversations in real-time. Review, analyze, and improve your bot's responses from one dashboard.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-graph-up"></i></div>
                    <h3 class="feature-title">Analytics & Insights</h3>
                    <p class="feature-description">Track resolution rates, popular questions, and user satisfaction. Make data-driven improvements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <span class="section-label">How It Works</span>
                <h2 class="section-title">Go live in 5 minutes</h2>
                <p class="section-description">Three simple steps to transform your customer support.</p>
            </div>
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Add Your Content</h3>
                    <p class="step-description">Upload your FAQs, help docs, or website URL. Our AI analyzes and learns from your content instantly.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Customize & Preview</h3>
                    <p class="step-description">Style your chatbot to match your brand. Test it out and refine responses until it's perfect.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Deploy & Relax</h3>
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
                    <div class="testimonial-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
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
                    <div class="testimonial-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
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
                    <div class="testimonial-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
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
                <p class="section-description">Start free. Upgrade when you're ready.</p>
            </div>
            <div class="pricing-toggle">
                <button class="pricing-toggle-btn active">Monthly</button>
                <button class="pricing-toggle-btn">Yearly</button>
            </div>
            <p class="pricing-save">Save 20% with yearly billing</p>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <h3 class="pricing-name">Free</h3>
                    <p class="pricing-description">Perfect for trying out BotPal</p>
                    <div class="pricing-price">
                        <span class="pricing-amount">$0</span>
                        <span class="pricing-period">/month</span>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Get Started</a>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check-lg"></i> 1 chatbot</li>
                        <li><i class="bi bi-check-lg"></i> 100 messages/month</li>
                        <li><i class="bi bi-check-lg"></i> Basic customization</li>
                        <li><i class="bi bi-check-lg"></i> Email support</li>
                    </ul>
                </div>
                <div class="pricing-card featured">
                    <span class="pricing-badge">Most Popular</span>
                    <h3 class="pricing-name">Pro</h3>
                    <p class="pricing-description">For growing businesses</p>
                    <div class="pricing-price">
                        <span class="pricing-amount">$29</span>
                        <span class="pricing-period">/month</span>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-primary">Start Free Trial</a>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check-lg"></i> Unlimited chatbots</li>
                        <li><i class="bi bi-check-lg"></i> 5,000 messages/month</li>
                        <li><i class="bi bi-check-lg"></i> Full customization</li>
                        <li><i class="bi bi-check-lg"></i> GPT-4 access</li>
                        <li><i class="bi bi-check-lg"></i> Analytics dashboard</li>
                        <li><i class="bi bi-check-lg"></i> Priority support</li>
                    </ul>
                </div>
                <div class="pricing-card">
                    <h3 class="pricing-name">Enterprise</h3>
                    <p class="pricing-description">For large organizations</p>
                    <div class="pricing-price">
                        <span class="pricing-amount">Custom</span>
                    </div>
                    <a href="{{ route('pages.contact') }}" class="btn btn-secondary">Contact Sales</a>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check-lg"></i> Everything in Pro</li>
                        <li><i class="bi bi-check-lg"></i> Unlimited messages</li>
                        <li><i class="bi bi-check-lg"></i> Custom integrations</li>
                        <li><i class="bi bi-check-lg"></i> SLA guarantee</li>
                        <li><i class="bi bi-check-lg"></i> Dedicated support</li>
                        <li><i class="bi bi-check-lg"></i> On-premise option</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section" id="faq">
        <div class="container">
            <div class="section-header">
                <span class="section-label">FAQ</span>
                <h2 class="section-title">Frequently asked questions</h2>
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
                <h2 class="cta-title">Ready to transform your customer support?</h2>
                <p class="cta-description">Join 1,000+ businesses using BotPal to deliver instant, intelligent support around the clock.</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-large">Start Free Trial</a>
                    <a href="{{ route('pages.contact') }}" class="btn btn-secondary btn-large" style="background: transparent; border-color: #475569; color: white;">Contact Sales</a>
                </div>
                <p class="cta-note">No credit card required. Free plan available forever.</p>
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
                    <p>AI-powered chatbots that help businesses deliver exceptional customer support around the clock.</p>
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
                        <li><a href="{{ route('pages.privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('pages.terms') }}">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="{{ route('pages.contact') }}">Help Center</a></li>
                        <li><a href="{{ route('pages.contact') }}">Contact Us</a></li>
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

// Pricing toggle (placeholder functionality)
document.querySelectorAll('.pricing-toggle-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.pricing-toggle-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>
@endpush
