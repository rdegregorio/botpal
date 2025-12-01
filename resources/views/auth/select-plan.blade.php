@extends('layouts.auth')

@section('content')
    <h1 class="auth-title">Choose Your Plan</h1>
    <p class="auth-subtitle">Select a plan to get started</p>

    <div class="plans-container">
        <!-- Free Plan -->
        <div class="plan-card plan-free">
            <div class="plan-header">
                <h3 class="plan-name">Free</h3>
                <div class="plan-price">
                    <span class="price">$0</span>
                    <span class="period">/month</span>
                </div>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check"></i> 1,000 messages/month</li>
                <li><i class="bi bi-check"></i> 1 Chatbot</li>
                <li><i class="bi bi-check"></i> FAQ Knowledge Base</li>
                <li><i class="bi bi-check"></i> Basic Analytics</li>
            </ul>
            <form action="{{ route('select-plan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="free">
                <button type="submit" class="btn-plan btn-plan-primary">Get Started</button>
            </form>
        </div>

        <!-- Premium Plan -->
        <div class="plan-card plan-premium">
            <div class="plan-badge">Popular</div>
            <div class="plan-header">
                <h3 class="plan-name">Premium</h3>
                <div class="plan-price">
                    <span class="price">$9.99</span>
                    <span class="period">/month</span>
                </div>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check"></i> 10,000 messages/month</li>
                <li><i class="bi bi-check"></i> Unlimited Chatbots</li>
                <li><i class="bi bi-check"></i> PDF & Plain Text KB</li>
                <li><i class="bi bi-check"></i> Advanced Analytics</li>
                <li><i class="bi bi-check"></i> Priority Support</li>
            </ul>
            <button type="button" class="btn-plan btn-plan-disabled" disabled>
                Coming Soon
            </button>
        </div>

        <!-- Enterprise Plan -->
        <div class="plan-card plan-enterprise">
            <div class="plan-header">
                <h3 class="plan-name">Enterprise</h3>
                <div class="plan-price">
                    <span class="price">Custom</span>
                </div>
            </div>
            <ul class="plan-features">
                <li><i class="bi bi-check"></i> Unlimited messages</li>
                <li><i class="bi bi-check"></i> Unlimited Chatbots</li>
                <li><i class="bi bi-check"></i> Custom Integrations</li>
                <li><i class="bi bi-check"></i> Dedicated Support</li>
                <li><i class="bi bi-check"></i> SLA Guarantee</li>
            </ul>
            <button type="button" class="btn-plan btn-plan-disabled" disabled>
                Coming Soon
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .auth-container {
        max-width: 900px;
    }

    .auth-card {
        padding: 40px 30px;
    }

    .plans-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 24px;
    }

    @media (max-width: 768px) {
        .plans-container {
            grid-template-columns: 1fr;
        }
    }

    .plan-card {
        background: var(--bg-cream);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        position: relative;
        transition: all 0.2s;
    }

    .plan-card:hover {
        border-color: #ccc;
    }

    .plan-premium {
        border-color: var(--text-primary);
        background: #fafafa;
    }

    .plan-badge {
        position: absolute;
        top: -10px;
        right: 20px;
        background: var(--text-primary);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .plan-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }

    .plan-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .plan-price .price {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .plan-price .period {
        font-size: 14px;
        color: var(--text-secondary);
    }

    .plan-features {
        list-style: none;
        padding: 0;
        margin: 0 0 24px 0;
    }

    .plan-features li {
        padding: 8px 0;
        font-size: 14px;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .plan-features li i {
        color: #22c55e;
        font-size: 16px;
    }

    .btn-plan {
        width: 100%;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-plan-primary {
        background: var(--text-primary);
        color: white;
    }

    .btn-plan-primary:hover {
        background: #333;
    }

    .btn-plan-disabled {
        background: #e5e5e5;
        color: #999;
        cursor: not-allowed;
    }
</style>
@endpush
