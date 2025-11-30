@php
    $subscription ??= null;
    $active = $subscription?->type;
@endphp

<div class="row mb-4 @if($subscription?->onTrial()) on-trial @endif">
    <div class="col-lg-4 col-12">
        <div class="plan-box free-plan @if($active === 4) active current-plan @endif">
            @include('includes.subscriptions.plans._free', ['showDeleteAccountBtn' => true])
        </div>
    </div>
    <div class="col-lg-4 col-12">
        <div class="plan-box basic-plan @if($active === 1) active current-plan @endif">
            @include('includes.subscriptions.plans._basic', ['showDeleteAccountBtn' => true])
        </div>
    </div>
    <div class="col-lg-4 col-12">
        <div class="plan-box premium-plan @if($active === 2) active current-plan @endif">
            @include('includes.subscriptions.plans._premium')
        </div>
    </div>
{{--    <div class="col-lg-4 col-12">--}}
{{--        <div class="plan-box business-plan @if($active === 3) active current-plan @endif">--}}
{{--            @include('includes.subscriptions.plans._business')--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
@push('bottom')
    <script src="https://js.stripe.com/v3/" async></script>

    <script>
        $(document).on('click', '.plan-box', function (e) {
            $('.plan-box').removeClass('selected');
            $(this).addClass('selected');
        });
    </script>
@endpush
