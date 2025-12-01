@php
    $subscription ??= null;
    $active = $subscription?->type;
@endphp

<style>
.pricing-grid-billing {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
.pricing-card-billing {
    background: white;
    border: 1px solid #e8e8e8;
    border-radius: 16px;
    padding: 32px;
    transition: all 0.2s;
    cursor: pointer;
}
.pricing-card-billing:hover {
    border-color: #999;
}
.pricing-card-billing.active {
    border-color: #1a1a1a;
    border-width: 2px;
}
.pricing-card-billing.featured {
    border-color: #1a1a1a;
    position: relative;
}
.pricing-badge-billing {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: #1a1a1a;
    color: white;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.pricing-name-billing {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 4px;
}
.pricing-description-billing {
    font-size: 13px;
    color: #999;
    margin-bottom: 20px;
}
.pricing-price-billing {
    margin-bottom: 24px;
}
.pricing-amount-billing {
    font-size: 40px;
    font-weight: 600;
    color: #1a1a1a;
}
.pricing-period-billing {
    font-size: 14px;
    color: #999;
}
.pricing-features-billing {
    list-style: none;
    padding: 0;
    margin-bottom: 24px;
}
.pricing-features-billing li {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    font-size: 14px;
    color: #6b6b6b;
}
.pricing-features-billing li i {
    color: #1a1a1a;
    font-size: 14px;
}
.current-badge {
    display: inline-block;
    background: #22c55e;
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 12px;
}
@media (max-width: 992px) {
    .pricing-grid-billing {
        grid-template-columns: 1fr;
        max-width: 400px;
        margin: 0 auto;
    }
}
</style>

<div class="pricing-grid-billing @if($subscription?->onTrial()) on-trial @endif">
    <!-- Free Plan -->
    <div class="pricing-card-billing @if($active === 4) active @endif" data-plan="free">
        @if($active === 4)
            <span class="current-badge">Current Plan</span>
        @endif
        <h3 class="pricing-name-billing">Free</h3>
        <p class="pricing-description-billing">Perfect for trying out BotPal</p>
        <div class="pricing-price-billing">
            <span class="pricing-amount-billing">$0</span>
            <span class="pricing-period-billing">/month</span>
        </div>
        <ul class="pricing-features-billing">
            <li><i class="bi bi-check2"></i> 1 chatbot</li>
            <li><i class="bi bi-check2"></i> 100 messages/month</li>
            <li><i class="bi bi-check2"></i> Basic customization</li>
            <li><i class="bi bi-check2"></i> Email support</li>
        </ul>
        @if($active !== 4)
            @include('includes.subscriptions._stripe-button', ['plan' => 'free', 'label' => 'Downgrade to Free'])
        @endif
    </div>

    <!-- Pro Plan -->
    <div class="pricing-card-billing featured @if($active === 1) active @endif" data-plan="basic">
        <span class="pricing-badge-billing">Popular</span>
        @if($active === 1)
            <span class="current-badge" style="margin-top: 8px;">Current Plan</span>
        @endif
        <h3 class="pricing-name-billing">Pro</h3>
        <p class="pricing-description-billing">For growing businesses</p>
        <div class="pricing-price-billing">
            <span class="pricing-amount-billing">$29</span>
            <span class="pricing-period-billing">/month</span>
        </div>
        <ul class="pricing-features-billing">
            <li><i class="bi bi-check2"></i> Unlimited chatbots</li>
            <li><i class="bi bi-check2"></i> 5,000 messages/month</li>
            <li><i class="bi bi-check2"></i> Full customization</li>
            <li><i class="bi bi-check2"></i> GPT-4 access</li>
            <li><i class="bi bi-check2"></i> Analytics dashboard</li>
            <li><i class="bi bi-check2"></i> Priority support</li>
        </ul>
        @if($active !== 1)
            @include('includes.subscriptions._stripe-button', ['plan' => 'basic', 'label' => $active ? 'Switch to Pro' : 'Start Pro Trial'])
        @else
            @include('includes.subscriptions._unsubscribe-btn-and-modal')
        @endif
    </div>

    <!-- Premium Plan -->
    <div class="pricing-card-billing @if($active === 2) active @endif" data-plan="premium">
        @if($active === 2)
            <span class="current-badge">Current Plan</span>
        @endif
        <h3 class="pricing-name-billing">Enterprise</h3>
        <p class="pricing-description-billing">For large organizations</p>
        <div class="pricing-price-billing">
            <span class="pricing-amount-billing">Custom</span>
        </div>
        <ul class="pricing-features-billing">
            <li><i class="bi bi-check2"></i> Everything in Pro</li>
            <li><i class="bi bi-check2"></i> Unlimited messages</li>
            <li><i class="bi bi-check2"></i> Custom integrations</li>
            <li><i class="bi bi-check2"></i> SLA guarantee</li>
            <li><i class="bi bi-check2"></i> Dedicated support</li>
            <li><i class="bi bi-check2"></i> On-premise option</li>
        </ul>
        <a href="{{ route('pages.contact') }}" class="btn btn-outline-dark w-100">Contact Sales</a>
    </div>
</div>

@push('bottom')
    <script src="https://js.stripe.com/v3/" async></script>
@endpush
