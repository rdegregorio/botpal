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

    @if($subscription && Auth::user()->stripe_id)
    <!-- Payment Details -->
    <div class="row">
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title"><i class="bi bi-credit-card me-2"></i>Payment Method</h2>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1"><strong>Card ending in:</strong> {{ Auth::user()->card_last_four ?? '••••' }}</p>
                        <p class="text-muted small mb-0">Your default payment method</p>
                    </div>
                    <button data-change-card class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Change
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title"><i class="bi bi-receipt me-2"></i>Billing History</h2>
                </div>
                <button id="get-invoices" onclick="invoicesModal()" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-file-text me-1"></i> View Invoices
                </button>
            </div>
        </div>
    </div>

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

    <!-- Usage Stats -->
    <div class="dashboard-card" id="usage">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-bar-chart me-2"></i>Usage Statistics</h2>
            <input type="text" id="datePicker" class="form-control" style="max-width: 150px;" placeholder="Select date">
        </div>
        <p class="text-muted mb-4">AI chatbot responses to user interactions</p>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="p-3 border rounded" style="background: var(--bg-cream);">
                    <canvas id="usageChart" height="200"></canvas>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded text-center" style="background: var(--bg-cream);">
                            <p class="text-muted small mb-1">Total Messages</p>
                            <h3 class="mb-0" id="totalMessages">-</h3>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded text-center" style="background: var(--bg-cream);">
                            <p class="text-muted small mb-1">This Month</p>
                            <h3 class="mb-0" id="thisMonthMessages">-</h3>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded text-center" style="background: var(--bg-cream);">
                            <p class="text-muted small mb-1">Daily Average</p>
                            <h3 class="mb-0" id="dailyAverage">-</h3>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded text-center" style="background: var(--bg-cream);">
                            <p class="text-muted small mb-1">Peak Day</p>
                            <h3 class="mb-0" id="peakDay">-</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.standalone.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        // Usage chart
        var ctx = document.getElementById('usageChart').getContext('2d');
        var usageChart;

        $('#datePicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            endDate: new Date()
        }).on('changeDate', function() {
            fetchData($("#datePicker").datepicker('getFormattedDate'));
        });

        function fetchData(startDate) {
            $.ajax({
                url: '{{ route('account.stats') }}',
                type: 'GET',
                data: { start_date: startDate },
                success: function(response) {
                    if (usageChart) usageChart.destroy();

                    usageChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: response.labels,
                            datasets: response.datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });

                    // Calculate stats
                    var data = response.datasets[0]?.data || [];
                    var total = data.reduce((a, b) => a + b, 0);
                    var avg = data.length ? Math.round(total / data.length) : 0;
                    var peak = Math.max(...data, 0);

                    $('#totalMessages').text(total);
                    $('#thisMonthMessages').text(total);
                    $('#dailyAverage').text(avg);
                    $('#peakDay').text(peak);
                }
            });
        }

        fetchData(null);
    </script>
@endpush
