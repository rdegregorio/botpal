<div class="plan-header">
    <div class="header-content">
        <div
            class="plan-title px-2 p-2">{{\App\Models\Subscription::getNameByType(\App\Models\Subscription::PLAN_BUSINESS)}}
            Plan
        </div>
        <div class="price">$
            @if($subscription && $subscription->isBusiness())
                ${{$subscription->getPrice()}}
            @else
                -
            @endif
            /month
        </div>
    </div>
</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>OpenAI Token Required</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>Usage Costs via OpenAI</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>Dedicated Account Manager</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>Flexible Billing Options</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>+ Premium Plan Features</div>
<div class="text-center py-3">

    @if(Auth::check() && $subscription)
        @if($subscription->isBusiness())
            <button class="btn btn-default btn-sm" disabled="">
                Selected
            </button>
            <button type="button" id="business-unsubscribe" class="btn btn-plan btn-business-subscribe-unsubscribe-modal" data-bs-toggle="modal" data-bs-target="#business-subscribe-unsubscribe-modal">
                Unsubscribe
            </button>
            @include('includes.subscriptions._business-subscribe-unsubscribe-modal', ['subscribe' => false])
        @else
            <button type="button" class="btn btn-plan btn-sm btn-business-subscribe-unsubscribe-modal" data-bs-toggle="modal" data-bs-target="#business-subscribe-unsubscribe-modal">
                Request Quote
            </button>
            @include('includes.subscriptions._business-subscribe-unsubscribe-modal')
        @endif
    @else
        <button type="button" class="btn btn-plan btn-sm btn-business-subscribe-unsubscribe-modal" data-bs-toggle="modal" data-bs-target="#business-subscribe-unsubscribe-modal">
            Request Quote
        </button>
        @include('includes.subscriptions._business-subscribe-unsubscribe-modal')
    @endif

</div>
