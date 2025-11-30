<div class="plan-header">
    <div class="header-content">
        <div
            class="plan-title px-2 p-2">{{\App\Models\Subscription::getNameByType(\App\Models\Subscription::PLAN_FREE)}}
            Plan
        </div>
        <div class="price">Free
        </div>
    </div>
</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>No CC - Free Plan</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>OpenAI Token Required</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>FAQ Knowledge Base</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>Custom Welcome Message</div>
<div class="plan-feature px-3 p-1"><i class="bi bi-check-circle px-2"></i>Easy Copy/Paste Embed Code</div>
<div class="text-center py-3">
    @if(Auth::check())
        @if( ! optional($subscription)->isBusiness())
            <form action="{{route('account.checkout.subscribe.free')}}" method="POST">
                @csrf
                @if($subscription?->type === \App\Models\Subscription::PLAN_FREE)
                    <button class="btn btn-plan" disabled="">
                        Selected
                    </button>
                @elseif($subscription)
                    <button type="button" disabled class="btn btn-plan selectplan">
                        Select Plan
                    </button>
                @else
                    <button type="submit" class="btn btn-plan selectplan">
                        Select Plan
                    </button>
                @endif
                @can('delete-account')
                    @include('includes.subscriptions._delete-account-btn-and-modal')
                @endcan
            </form>
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
