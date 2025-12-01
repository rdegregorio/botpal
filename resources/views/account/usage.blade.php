@extends('layouts.dashboard')

@section('page-title', 'Usage & Stats')

@section('content')
    @php
        $limit = $subscription->default_limit ?? 0;
        $used = $subscription->requests_count ?? 0;
        $remaining = max(0, $limit - $used);
        $percentUsed = $limit > 0 ? min(100, round(($used / $limit) * 100)) : 0;
        $expiresAt = $subscription->expires_at;
    @endphp

    <!-- Plan Credits Overview -->
    <div class="dashboard-card mb-4">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-credit-card me-2"></i>Your Plan Credits</h2>
            <span class="badge bg-dark">{{ $subscription->getName() }} Plan</span>
        </div>

        <div class="row mt-4">
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <p class="text-muted small mb-1">Monthly Limit</p>
                    <h3 class="mb-0">{{ number_format($limit) }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <p class="text-muted small mb-1">Used</p>
                    <h3 class="mb-0 text-primary">{{ number_format($used) }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <p class="text-muted small mb-1">Remaining</p>
                    <h3 class="mb-0 {{ $remaining < ($limit * 0.1) ? 'text-danger' : 'text-success' }}">{{ number_format($remaining) }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <p class="text-muted small mb-1">Resets On</p>
                    <h3 class="mb-0" style="font-size: 1.25rem;">{{ $expiresAt ? $expiresAt->format('M j, Y') : 'N/A' }}</h3>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <div class="d-flex justify-content-between mb-1">
                <small class="text-muted">Usage Progress</small>
                <small class="text-muted">{{ $percentUsed }}%</small>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar {{ $percentUsed > 90 ? 'bg-danger' : ($percentUsed > 70 ? 'bg-warning' : 'bg-success') }}"
                     role="progressbar"
                     style="width: {{ $percentUsed }}%"
                     aria-valuenow="{{ $percentUsed }}"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
        </div>

        @if($subscription->isFree())
            <div class="mt-3 p-3 rounded" style="background: #fef3c7;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-lightning-charge text-warning me-2"></i>
                    <span>Need more messages? <a href="{{ route('pricing') }}" class="fw-bold">Upgrade to Premium</a> for 10,000 messages/month!</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Usage Stats -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-bar-chart me-2"></i>Usage Statistics</h2>
            <input type="text" id="datePicker" class="form-control" style="max-width: 150px;" placeholder="Select date">
        </div>
        <p class="text-muted mb-4">AI chatbot responses to user interactions</p>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="p-3 border rounded" style="background: var(--bg-cream);">
                    <canvas id="usageChart" height="250"></canvas>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded text-center" style="background: var(--bg-cream);">
                            <p class="text-muted small mb-1">Total Messages</p>
                            <h3 class="mb-0" id="totalMessages">-</h3>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded text-center" style="background: var(--bg-cream);">
                            <p class="text-muted small mb-1">This Period</p>
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

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="dashboard-card mb-0">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded me-3" style="background: #dbeafe;">
                        <i class="bi bi-chat-dots text-primary" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Total Conversations</p>
                        <h4 class="mb-0" id="totalConversations">-</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="dashboard-card mb-0">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded me-3" style="background: #dcfce7;">
                        <i class="bi bi-check-circle text-success" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Responses Sent</p>
                        <h4 class="mb-0" id="responsesSent">-</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="dashboard-card mb-0">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded me-3" style="background: #fef3c7;">
                        <i class="bi bi-graph-up text-warning" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Avg. Response Time</p>
                        <h4 class="mb-0">&lt; 1s</h4>
                    </div>
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
                            datasets: [{
                                label: 'AI Bot Responses',
                                data: response.datasets[0]?.data || [],
                                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            },
                            plugins: {
                                legend: { display: true, position: 'top' }
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
                    $('#totalConversations').text(total);
                    $('#responsesSent').text(total);
                }
            });
        }

        fetchData(null);
    </script>
@endpush
