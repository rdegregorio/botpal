@extends('layouts.auth')

@section('content')
    <h1 class="auth-title">Choose Your Plan</h1>
    <p class="auth-subtitle">Select a plan to get started</p>

    <div class="plans-container">
        <!-- Free Plan -->
        <div class="plan-card">
            <h3 class="plan-name">Free</h3>
            <p class="plan-description">Perfect for trying out aisupport.bot</p>
            <div class="plan-price">
                <span class="price">$0</span>
                <span class="period">/month</span>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check2"></i> 1,000 messages/month</li>
                <li><i class="bi bi-check2"></i> 1 Chatbot</li>
                <li><i class="bi bi-check2"></i> FAQ Knowledge Base</li>
                <li><i class="bi bi-check2"></i> Basic Analytics</li>
            </ul>
            <form action="{{ route('select-plan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="free">
                <button type="submit" class="btn-plan btn-plan-outline">Get started</button>
            </form>
        </div>

        <!-- Premium Plan -->
        <div class="plan-card plan-featured">
            <span class="plan-badge">POPULAR</span>
            <h3 class="plan-name">Premium</h3>
            <p class="plan-description">For growing businesses</p>
            <div class="plan-price">
                <span class="price">$19.99</span>
                <span class="period">/month</span>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check2"></i> 10,000 messages/month</li>
                <li><i class="bi bi-check2"></i> Unlimited Chatbots</li>
                <li><i class="bi bi-check2"></i> PDF & Plain Text KB</li>
                <li><i class="bi bi-check2"></i> Advanced Analytics</li>
                <li><i class="bi bi-check2"></i> Priority Support</li>
            </ul>
            <a href="{{ route('stripe.checkout') }}" class="btn-plan btn-plan-primary">Get started</a>
        </div>

        <!-- Enterprise Plan -->
        <div class="plan-card">
            <h3 class="plan-name">Enterprise</h3>
            <p class="plan-description">For large organizations</p>
            <div class="plan-price">
                <span class="price">Custom</span>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check2"></i> Unlimited messages</li>
                <li><i class="bi bi-check2"></i> Unlimited Chatbots</li>
                <li><i class="bi bi-check2"></i> Custom Integrations</li>
                <li><i class="bi bi-check2"></i> Dedicated Support</li>
                <li><i class="bi bi-check2"></i> SLA Guarantee</li>
                <li><i class="bi bi-check2"></i> On-premise option</li>
            </ul>
            <button type="button" class="btn-plan btn-plan-disabled" disabled>Coming Soon</button>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .auth-container {
        max-width: 960px;
    }

    .auth-card {
        padding: 48px 40px;
        background: #f8f8f8;
    }

    .auth-title {
        font-size: 28px;
        margin-bottom: 8px;
    }

    .auth-subtitle {
        color: #6b6b6b;
        margin-bottom: 32px;
    }

    .plans-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    @media (max-width: 768px) {
        .plans-container {
            grid-template-columns: 1fr;
        }
    }

    .plan-card {
        background: white;
        border: 1px solid #e8e8e8;
        border-radius: 16px;
        padding: 32px;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .plan-featured {
        border: 2px solid #1a1a1a;
    }

    .plan-badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: #1a1a1a;
        color: white;
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .plan-name {
        font-size: 20px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .plan-description {
        font-size: 13px;
        color: #999;
        margin-bottom: 20px;
    }

    .plan-price {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e8e8e8;
    }

    .plan-price .price {
        font-size: 42px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .plan-price .period {
        font-size: 14px;
        color: #999;
    }

    .plan-features {
        list-style: none;
        padding: 0;
        margin: 0 0 32px 0;
        flex: 1;
    }

    .plan-features li {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        font-size: 14px;
        color: #6b6b6b;
    }

    .plan-features li i {
        color: #1a1a1a;
        font-size: 16px;
    }

    .btn-plan {
        width: 100%;
        padding: 14px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        text-align: center;
        display: inline-block;
    }

    .btn-plan-primary {
        background: #1a1a1a;
        color: white;
        border: 2px solid #1a1a1a;
    }

    .btn-plan-primary:hover {
        background: #333;
        border-color: #333;
        color: white;
    }

    .btn-plan-outline {
        background: white;
        color: #1a1a1a;
        border: 2px solid #1a1a1a;
    }

    .btn-plan-outline:hover {
        background: #f5f5f5;
    }

    .btn-plan-disabled {
        background: #f5f5f5;
        color: #999;
        border: 2px solid #e8e8e8;
        cursor: not-allowed;
    }
</style>
@endpush
