@extends('layouts.dashboard')

@section('page-title', 'Billing & Plans')

@section('content')
    @unless($subscription)
        <div class="alert alert-warning mb-4">
            Your account is inactive. Please select a plan below to activate the service.
        </div>
    @endunless

    <!-- Current Plan -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title">Your Plan</h2>
        </div>

        @include('includes.subscriptions._plans-selector')
    </div>

    <!-- Payment Details -->
    <div class="row">
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title"><i class="bi bi-credit-card me-2"></i>Payment Method</h2>
                </div>
                @if($subscription && Auth::user()->stripe_id && Auth::user()->card_last_four)
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1"><strong>Card ending in:</strong> {{ Auth::user()->card_last_four }}</p>
                        <p class="text-muted small mb-0">Your default payment method</p>
                    </div>
                    <button data-change-card class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Change
                    </button>
                </div>
                @else
                <div class="text-center py-4" style="background: var(--bg-cream); border-radius: 8px;">
                    <i class="bi bi-credit-card" style="font-size: 32px; color: var(--text-secondary);"></i>
                    <p class="mt-2 mb-0 text-muted">No payment method on file</p>
                    <small class="text-muted">Add a card when you upgrade to a paid plan</small>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title"><i class="bi bi-receipt me-2"></i>Billing History</h2>
                </div>
                @if($subscription && Auth::user()->stripe_id)
                <button id="get-invoices" onclick="invoicesModal()" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-file-text me-1"></i> View Invoices
                </button>
                @else
                <div class="text-center py-4" style="background: var(--bg-cream); border-radius: 8px;">
                    <i class="bi bi-receipt" style="font-size: 32px; color: var(--text-secondary);"></i>
                    <p class="mt-2 mb-0 text-muted">No billing history</p>
                    <small class="text-muted">Invoices will appear here after your first payment</small>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($subscription && Auth::user()->stripe_id)
    <!-- Subscription Details -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-calendar me-2"></i>Subscription Details</h2>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <p class="text-muted small mb-1">Start Date</p>
                <p class="mb-0"><strong>{{ $subscription->created_at->format('F jS, Y') }}</strong></p>
            </div>
            @if(!$subscription->canceled())
            <div class="col-md-4 mb-3">
                <p class="text-muted small mb-1">Next Billing Date</p>
                <p class="mb-0"><strong>{{ $subscription->expires_at->format('F jS, Y') }}</strong></p>
            </div>
            @endif
            @if($subscription->onTrial())
            <div class="col-md-4 mb-3">
                <p class="text-muted small mb-1">Trial Ends</p>
                <p class="mb-0"><strong>{{ $subscription->trial_ends_at->format('F jS, Y') }}</strong></p>
            </div>
            @endif
            @if($subscription->ends_at)
            <div class="col-md-4 mb-3">
                <p class="text-muted small mb-1">Subscription Ends</p>
                <p class="mb-0 text-danger"><strong>{{ $subscription->ends_at->format('F jS, Y') }}</strong></p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Invoices Modal -->
    <div class="modal fade" id="invoices" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Invoices</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    Loading...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <script>
        // Change card handler
        $(document).on('click', '[data-change-card]', function () {
            $(this).prop('disabled', true);
            $.post('{{ route('account.checkout.get-change-card-session-id') }}', {
                _token: '{{ csrf_token() }}'
            }, function (e) {
                var stripe = Stripe('{{ config('cashier.key') }}');
                stripe.redirectToCheckout({
                    sessionId: e.sessionId
                });
            }).fail(function (e) {
                alert(e.responseJSON?.error || 'Error');
                $('[data-change-card]').prop('disabled', false);
            });
        });

        // Invoices modal
        function invoicesModal() {
            var $modal = $('#invoices');
            $modal.modal('show');

            $.get('{{ route('account.invoices') }}', function (res) {
                var html = '<table class="table table-sm"><tbody>';
                if (res.length === 0) {
                    html = '<p class="text-muted">No invoices yet.</p>';
                } else {
                    for (var i = 0; i < res.length; i++) {
                        var item = res[i];
                        html += '<tr><td>' + item.date + '</td><td>' + item.total + '</td><td><a href="' + item.url + '" target="_blank" class="btn btn-sm btn-outline-primary">View</a></td></tr>';
                    }
                    html += '</tbody></table>';
                }
                $modal.find('.modal-body').html(html);
            });
        }
    </script>
@endpush
