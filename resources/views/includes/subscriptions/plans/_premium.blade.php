<div class="plan-header">
    <div class="header-content">
        <div
            class="plan-title px-2 p-2">{{\App\Models\Subscription::getNameByType(\App\Models\Subscription::PLAN_PREMIUM)}}
            Plan
        </div>
        <div class="price">${{\App\Models\Subscription::getPriceByType(\App\Models\Subscription::PLAN_PREMIUM)}}/month
        </div>
    </div>
</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>OpenAI Token Required</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>Premium Technical Support</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>Annual Discount Option</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>Dedicated Account Manager</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>+ Basic Plan Features</div>
<div class="text-center py-3">
    @if(Auth::check())
        @if( ! optional($subscription)->isBusiness())
            @include('includes.subscriptions._stripe-button', [
                'planType' => \App\Models\Subscription::PLAN_PREMIUM,
            ])
        @else
            <a href="{{route('forms.contact')}}" class="btn btn-plan">
                Downgrade
            </a>
        @endif
    @else
        <a href="{{route('register')}}" class="btn btn-plan">
            Select Plan
        </a>
    @endif
</div>
