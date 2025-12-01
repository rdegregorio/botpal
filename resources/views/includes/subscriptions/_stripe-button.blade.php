@php
    /** @var \App\Models\Subscription $subscription */
    $planType = $planType ?? null;
    $subscription = $subscription ?? null;
    $amount = \App\Models\Subscription::getPriceByType($planType);
    $name = \App\Models\Subscription::getNameByType($planType);
    $planName = \App\Models\Subscription::getPlanNameByType($planType);
    $showDeleteAccountBtn = $showDeleteAccountBtn ?? false;
@endphp

@if($subscription && $subscription->type === $planType)
    <button class="btn btn-plan" disabled="">
        Selected
    </button>

    @if($subscription->onTrial())
        <form id="activate" method="post" action="{{route('account.activate')}}" class="d-inline-block">
            {{csrf_field()}}
            <button class="btn btn-plan">Activate</button>
        </form>

        @push('bottom')

            <div class="modal fade" style="font-size: 17px;" id="activate-modal" tabindex="-1" role="dialog"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <strong class="modal-title">Activate</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to end your trial and activate your subscription?
                        </div>
                        <div class="modal-footer">
                            <button class="btn-success btn btn-sm activate-btn">Activate</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(function (e) {
                    var $activateModal = $('#activate-modal');

                    $('#activate').submit(function (e) {
                        e.preventDefault();
                        $activateModal.modal('show');
                    });

                    $activateModal.find('.activate-btn').click(function (e) {
                        $('#activate').unbind('submit').submit();
                    });
                });
            </script>
        @endpush
    @endif
    @if($subscription->onGracePeriod())
        <form id="resume" method="post" action="{{route('account.resume')}}" class="d-inline-block">
            {{csrf_field()}}
            <button class="btn btn-plan" style="width: 100%; white-space: pre;">Resume</button>
        </form>

        @can('delete-account')
            @include('includes.subscriptions._delete-account-btn-and-modal')
        @endcan
    @else
        @include('includes.subscriptions._unsubscribe-btn-and-modal')
    @endif
@elseif($subscription)
    <form id="change-{{$planType}}" method="post" action="{{route('account.swap')}}">
        {{csrf_field()}}
        <input type="hidden" name="planType" value="{{$planType}}">
        <button class="btn btn-plan">
            @if($subscription->price > $amount)
            Downgrade
            @elseif(Auth::user()->can('subscribe-trial'))
            Upgrade
            @else
            Activate
            @endif
        </button>
    </form>

    @push('bottom')
        <script>
            $('#change-{{$planType}}').submit(function (e) {
                e.preventDefault();
                const $form = $(this);

                @if($subscription->isFree())
                    @if(Auth::user()->can('subscribe-trial'))
                    const message = 'You\'ll be prompt to enter your CC details to start your free trial';
                    @else
                    const message = 'You\'ll be prompt to enter your CC details without trial.';
                    @endif
                @else
                const message = 'Upgrades and Downgrades get pro-rated at the end of your subscription period. Are you sure you want to change your subscription?';
                @endif

                if (confirm(message)) {
                  $form.find('.btn-plan').prop('disabled', true);
                  $.post($form.attr('action'), $form.serialize(), function (e) {
                    $.post('{{route('account.checkout.get-session-id')}}', {
                      plan: '{{$planName}}',
                      _token: '{{csrf_token()}}'
                    }, function (e) {
                      //redirect to stripe checkout
                      var stripe = Stripe('{{config('cashier.key')}}');
                      stripe.redirectToCheckout({
                        sessionId: e.sessionId
                      }).then(function (result) {
                        console.log(result);
                      });
                    }).fail(function (e) {
                      console.log(e.responseJSON.error);
                      $form.find('.btn-plan').prop('disabled', false);
                    });
                  });
                }
            });
        </script>
    @endpush
@else
    @push('bottom')
        <script>
            $(document).on('click', '[data-plan="{{$planName}}"]', function () {
                $(this).prop('disabled', true);
                //get stripe session_id
                $.post('{{route('account.checkout.get-session-id')}}', {
                    plan: $(this).data('plan'),
                    _token: '{{csrf_token()}}'
                }, function (e) {
                    //redirect to stripe checkout
                    var stripe = Stripe('{{config('cashier.key')}}');
                    stripe.redirectToCheckout({
                        sessionId: e.sessionId
                    }).then(function (result) {
                        console.log(result);
                    });
                }).fail(function (e) {
                    console.log(e.responseJSON.error);
                });
            });
        </script>
    @endpush
    <button type="button" data-plan="{{$planName}}" class="btn btn-plan selectplan">
        @can('subscribe-trial')
            Select Plan
        @else
            Select Plan
        @endcan
    </button>
    @if($showDeleteAccountBtn)
        @can('delete-account')
            @include('includes.subscriptions._delete-account-btn-and-modal')
        @endcan
    @endif
@endif
