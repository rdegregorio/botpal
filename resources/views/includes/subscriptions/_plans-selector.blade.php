@php
    $subscription ??= null;
    $active = $subscription?->type;
    $isPremium = $active === \App\Models\Subscription::TYPE_PREMIUM;
    $isFree = $active === \App\Models\Subscription::TYPE_FREE;
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
.btn-plan-billing {
    width: 100%;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid #1a1a1a;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}
.btn-plan-billing.primary {
    background: #1a1a1a;
    color: white;
}
.btn-plan-billing.primary:hover {
    background: #333;
    color: white;
}
.btn-plan-billing.outline {
    background: white;
    color: #1a1a1a;
}
.btn-plan-billing.outline:hover {
    background: #f5f5f5;
}
.btn-plan-billing:disabled, .btn-plan-billing.disabled {
    background: #e5e5e5;
    border-color: #e5e5e5;
    color: #999;
    cursor: not-allowed;
    pointer-events: none;
}
@media (max-width: 992px) {
    .pricing-grid-billing {
        grid-template-columns: 1fr;
        max-width: 400px;
        margin: 0 auto;
    }
}
</style>

<div class="pricing-grid-billing">
    <!-- Free Plan -->
    <div class="pricing-card-billing @if($isFree) active @endif">
        @if($isFree)
            <span class="current-badge">Current Plan</span>
        @endif
        <h3 class="pricing-name-billing">Free</h3>
        <p class="pricing-description-billing">Perfect for trying out aisupport.bot</p>
        <div class="pricing-price-billing">
            <span class="pricing-amount-billing">$0</span>
            <span class="pricing-period-billing">/month</span>
        </div>
        <ul class="pricing-features-billing">
            <li><i class="bi bi-check2"></i> 1 chatbot</li>
            <li><i class="bi bi-check2"></i> 1,000 messages/month</li>
            <li><i class="bi bi-check2"></i> Basic customization</li>
            <li><i class="bi bi-check2"></i> Email support</li>
        </ul>
        @if($isFree)
            <span class="btn-plan-billing disabled">Current Plan</span>
        @elseif($isPremium)
            <button type="button" class="btn-plan-billing outline" data-bs-toggle="modal" data-bs-target="#downgradeModal">Downgrade to Free</button>
        @else
            <form action="{{ route('account.checkout.subscribe.free') }}" method="POST">
                @csrf
                <button type="submit" class="btn-plan-billing outline">Select Free</button>
            </form>
        @endif
    </div>

    <!-- Premium Plan -->
    <div class="pricing-card-billing featured @if($isPremium) active @endif">
        <span class="pricing-badge-billing">Popular</span>
        @if($isPremium)
            <span class="current-badge" style="margin-top: 8px;">Current Plan</span>
        @endif
        <h3 class="pricing-name-billing">Premium</h3>
        <p class="pricing-description-billing">For growing businesses</p>
        <div class="pricing-price-billing">
            <span class="pricing-amount-billing">$19.99</span>
            <span class="pricing-period-billing">/month</span>
        </div>
        <ul class="pricing-features-billing">
            <li><i class="bi bi-check2"></i> Unlimited chatbots</li>
            <li><i class="bi bi-check2"></i> 10,000 messages/month</li>
            <li><i class="bi bi-check2"></i> Full customization</li>
            <li><i class="bi bi-check2"></i> GPT-4 access</li>
            <li><i class="bi bi-check2"></i> Analytics dashboard</li>
            <li><i class="bi bi-check2"></i> Priority support</li>
        </ul>
        @if($isPremium)
            <span class="btn-plan-billing disabled">Current Plan</span>
            <button type="button" class="btn-plan-billing outline mt-2" data-bs-toggle="modal" data-bs-target="#cancelModal">Cancel Subscription</button>
        @else
            <a href="{{ route('stripe.checkout') }}" class="btn-plan-billing primary">
                @if($isFree) Upgrade to Premium @else Subscribe to Premium @endif
            </a>
        @endif
    </div>

    <!-- Enterprise Plan -->
    <div class="pricing-card-billing">
        <h3 class="pricing-name-billing">Enterprise</h3>
        <p class="pricing-description-billing">For large organizations</p>
        <div class="pricing-price-billing">
            <span class="pricing-amount-billing">Custom</span>
        </div>
        <ul class="pricing-features-billing">
            <li><i class="bi bi-check2"></i> Everything in Premium</li>
            <li><i class="bi bi-check2"></i> Unlimited messages</li>
            <li><i class="bi bi-check2"></i> Custom integrations</li>
            <li><i class="bi bi-check2"></i> SLA guarantee</li>
            <li><i class="bi bi-check2"></i> Dedicated support</li>
            <li><i class="bi bi-check2"></i> On-premise option</li>
        </ul>
        <a href="{{ route('pages.contact') }}" class="btn-plan-billing outline">Contact Sales</a>
    </div>
</div>

<!-- Downgrade Modal -->
@if($isPremium)
<div class="modal fade" id="downgradeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Downgrade to Free</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('account.cancel') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to downgrade to the Free plan?</p>
                    <p class="text-muted">You will lose access to:</p>
                    <ul class="text-muted">
                        <li>10,000 messages/month (reduced to 1,000)</li>
                        <li>Unlimited chatbots</li>
                        <li>Priority support</li>
                    </ul>
                    <textarea name="reason" class="form-control" rows="3" placeholder="Tell us why you're downgrading (optional)"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Premium</button>
                    <button type="submit" class="btn btn-warning">Downgrade to Free</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('account.cancel') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>We're sorry to see you go! Please let us know why you're cancelling:</p>
                    <textarea name="reason" class="form-control" rows="4" required placeholder="Your feedback helps us improve..."></textarea>
                    <p class="mt-3 text-muted small">Your subscription will remain active until the end of your current billing period.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Subscription</button>
                    <button type="submit" class="btn btn-danger">Cancel Subscription</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
