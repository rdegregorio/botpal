@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12  mx-auto">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h4 class="mt-0 mb-2"><i class="bi bi-person"></i> Account</h4>
                    <div class="form-group mb-3">
                        <div data-editable>
                            <input type="text" readonly class="form-control" name="name" id="name"
                                   value="{{Auth::user()->name}}">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div data-editable>
                            <input type="email" readonly class="form-control" name="email" id="email"
                                   value="{{Auth::user()->email}}">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div data-editable>
                            <input type="password" readonly class="form-control" name="password" id="password"
                                   placeholder="*******">
                        </div>
                    </div>

                    <div class="open-ai-details">
                        <h4 id="plan" class="mt-4 mb-2"><i class="bi bi-key"></i> OpenAI Key</h4>

                        <div class="row align-items-end">
                            <div class="col-9">
                                <div class="form-group mb-2">
                                    <div data-editable>
                                        <input type="text" readonly autocomplete="off" class="form-control" name="open_ai_token" id="open_ai_token"
                                               placeholder="{{Auth::user()->open_ai_token ? '********' : 'Please paste your OPEN AI API token here'}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 pb-3">
                                <a href="https://platform.openai.com/signup" class="text-decoration-none" target="_blank">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Get an OpenAI Key
                                </a>
                            </div>
                        </div>

                    <h4 id="plan" class="mt-4 mb-2"><i class="bi bi-cpu"></i> OpenAI Model <i class="bi bi-info-circle" data-bs-toggle="modal" data-bs-target="#questionModal" style="color: #D5103E; cursor: pointer;"></i></h4>
                        <div class="row align-items-end px-1">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input
                                            @checked(Auth::user()->open_ai_model === \App\Services\OpenAIService::MODEL_35_TURBO)
                                            class="form-check-input" value="{{\App\Services\OpenAIService::MODEL_35_TURBO}}" type="radio" name="open_ai_model" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Chat GPT 3.5
                                    </label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input
                                            @checked(Auth::user()->open_ai_model === \App\Services\OpenAIService::MODEL_4_PREVIEW)
                                            class="form-check-input" value="{{\App\Services\OpenAIService::MODEL_4_PREVIEW}}" type="radio" name="open_ai_model" id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Chat GPT 4
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <!-- Modal Explanation Models -->
                <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="questionModalLabel">Which model should you use?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>All usage costs are billed through OpenAI. On your OpenAI's account, you can find comprehensive information regarding the costs associated with your token.</p>
                                <b><u>GPT-3.5:</u></b>
                                <p>GPT-3.5 is a cost-effective solution for various language tasks. It's ideal for straightforward queries and basic customer support. While it provides good performance for standard applications, it may be less sophisticated compared to GPT-4. One of its significant advantages is its affordability, being 10 times less expensive than GPT-4. It's a great choice for simple FAQs.</p>

                                <b><u>GPT-4:</u></b>
                                <p>GPT-4 represents a significant advancement in natural language processing. It excels in complex conversation and context understanding, making it highly suitable for tasks that require creativity and intricate problem-solving. It's equipped with cutting-edge technology and is 10 times more expensive than GPT-3.5. If your use case demands advanced understanding and creativity, GPT-4 may be the preferred choice.</p>
                                <p>The best approach is to test both models and see which one works best for your specific use case.</p>
                            </div>
                        </div>
                    </div>
                </div>

                    @unless($subscription)
                        <div class="mt-4 pt-2"></div>
                        <div class="alert alert-danger p-2 mt-4">
                            Your account is inactive. Please select a plan below to activate the service.
                        </div>
                    @endunless

                    <h4 id="plan" class="mt-4 mb-2"><i class="bi bi-list-check"></i> Your Plan</h4>

                    @include('includes.subscriptions._plans-selector')

                    @if($subscription)
                        @if(Auth::user()->stripe_id)
                            <div style="display: inline-block">
                                <h4 id="billing" class="mt-2 mb-2"><i class="bi bi-credit-card"></i> Payment details</h4>
                            </div>
                            <div style="float: right" class="mt-2">
                                <button id="get-invoices" onclick="invoicesModal()" class="btn btn-default btn-sm menu-inactive" title="Invoices">Invoices</button>
                            </div>
                            <table class="table">
                                <tbody>
                                <tr style="border-top: 0px solid #EEE;">
                                    <td>Last 4 numbers CC:</td>
                                    <td>
                                        {{Auth::user()->card_last_four}}
                                        &nbsp;&nbsp;
                                        <button data-change-card class="btn btn-link btn-sm" style="padding-top: 0px !important; padding-bottom: 0px !important;">
                                            Change
                                        </button>
                                        @push('bottom')
                                            <script>
                                                $(document).on('click', '[data-change-card]', function () {
                                                    $(this).prop('disabled', true);
                                                    //get stripe session_id
                                                    $.post('{{route('account.checkout.get-change-card-session-id')}}', {
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
                                    </td>
                                </tr>
                                <tr>
                                    <td>Start Date:</td>
                                    <td>{{$subscription->created_at->format('F jS Y')}}</td>
                                </tr>
                                @if( ! $subscription->canceled())
                                    <tr>
                                        <td>Next Billing Date:</td>
                                        <td>{{$subscription->expires_at->format('F jS Y')}}</td>
                                    </tr>
                                @endif
                                @if($subscription->onTrial())
                                    <tr>
                                        <td>Trial End Date:</td>
                                        <td>{{$subscription->trial_ends_at->format('F jS Y')}}</td>
                                    </tr>
                                @endif
                                @if($subscription->ends_at)
                                    <tr>
                                        <td>End Date:</td>
                                        <td>{{$subscription->ends_at->format('F jS Y')}}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>



                            @push('bottom')
                                <div class="modal fade" style="font-size: 17px;" id="invoices" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <strong class="modal-title" id="exampleModalLabel">Invoices</strong>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" style="overflow: auto; max-height: 75vh;">
                                                Loading...
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <style>
                                    #invoices tr, #invoices td {
                                        padding: 5px !important;
                                    }
                                </style>

                                <script>
                                    function invoicesModal(e) {
                                        var $modal = $('#invoices');
                                        $modal.modal('show');

                                        $.get('{{route('account.invoices')}}', function (res) {
                                            var html = '<table class="table"><tbody>';
                                            for (var i = 0; i < res.length; i++) {
                                                var item = res[i];
                                                html += '<tr>\n' +
                                                    '            <td>'+item.date+'</td>\n' +
                                                    '            <td>'+item.total+'</td>\n' +
                                                    '            <td class="text-right"><a target="_blank" href="'+item.url+'">Click here</a></td>\n' +
                                                    '        </tr>'
                                            }
                                            html += '</tbody></table>';

                                            $modal.find('.modal-body').html(html);
                                        })
                                    }
                                </script>
                            @endpush
                        @endif
                        @php
                            $requestsCount = $subscription->onTrial()
                                ? $subscription->trial_requests_count
                                : $subscription->requests_count;

                            if($requestsCount && $subscription->default_limit)
                                $percent = (int)($requestsCount / $subscription->default_limit * 100);
                            else
                                $percent = 0;
                        @endphp

                        <div class="row mt-3">
                            <h4 class="mt-4 mb-2"><i class="bi bi-bar-chart"></i> This period's usage </h4>
                            <div class="container mt-0 mb-4">
                                <div class="row">
                                    <div class="col-8"> AI chatbot responses to user interactions. </div>
                                    <div class="col-4 text-right"> <input type="text" id="datePicker" class="form-control" style="float: right;"> </div>
                                </div>
                                <div class="row">
                                    <div class="col-12"> <canvas id="usageChart"></canvas> </div>
                                </div>
                            </div>


                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.standalone.min.css" integrity="sha512-D5/oUZrMTZE/y4ldsD6UOeuPR4lwjLnfNMWkjC0pffPTCVlqzcHTNvkn3dhL7C0gYifHQJAIrRTASbMvLmpEug==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('usageChart').getContext('2d');
        var usageChart;
        $('#datePicker').datepicker({
          format: 'yyyy-mm-dd',
          todayHighlight: true,
          autoclose: true,
          beforeShowDay: function(date) {
            var today = new Date();
            today.setHours(0,0,0,0);

            if (date < today) {
              return {
                enabled: true,
                classes: ''
              };
            } else {
              return {
                enabled: false,
                classes: 'disabled-date'
              };
            }
          }
        }).on('changeDate', function(e) {
          var selectedDate = $("#datePicker").datepicker('getFormattedDate');
          fetchData(selectedDate);
        });

        function fetchData(startDate, endDate) {
          $.ajax({
            url: '{{route('account.stats')}}',
            type: 'GET',
            data: {
              start_date: startDate,
              end_date: endDate
            },
            success: function(response) {
              if (usageChart) {
                usageChart.destroy();
              }
              usageChart = new Chart(ctx, {
                type: 'bar',
                data: {
                  labels: response.labels,
                  datasets: response.datasets
                },
                options: {
                  scales: {
                    y: {
                      beginAtZero: true
                    }
                  }
                }
              });
            },
            error: function(error) {
              console.log('Error fetching and parsing data', error);
            }
          });
        }

        fetchData(null, null);
    </script>
    <script>
        $(function(e) {
          $('input:read-only, textarea:read-only').click(function () {
            $(this).closest('[data-editable]')?.find('[data-edit]')?.click();
          });
          $('[data-editable]').mouseleave(function () {
            if ($(this).data('edit-mode')) {
              return;
            }
            $(this).find('.edit-buttons').remove();
          });

          $('[data-editable]').mouseover(function () {

            if ($(this).find('.edit-buttons').length) {
              return;
            }

            var $buttons = $('\n' +
                '<div class="edit-buttons">\n' +
                '    <button data-edit class="btn btn-sm btn-primary">Edit</button>\n' +
                '</div>');

            $(this).append($buttons);
          });

          $(document).on('click', '[data-editable] [data-edit]', function () {
            var $block = $(this).closest('[data-editable]');
            $block.data('edit-mode', true);
            $block.find('input').prop('readonly', false).focus();

            var buttons = '<button data-save class="btn btn-sm btn-success">Save</button>\n' +
                '<button data-cancel class="btn btn-sm btn-default">Cancel</button>';

            $block.find('.edit-buttons').html(buttons);
          });

          $(document).on('click', '[data-editable] [data-cancel]', function () {
            var $block = $(this).closest('[data-editable]');
            $block.data('edit-mode', false);
            $block.find('input').prop('readonly', true);

            $block.find('.edit-buttons').remove();
          });

          $(document).on('click', '[data-editable] [data-save]', function () {
            var $block = $(this).closest('[data-editable]');
            $block.data('edit-mode', false);
            $block.find('input').prop('readonly', true);

            var field = $block.find('input').attr('name');

            var data = {
              field: field,
              value: $block.find('input').val(),
              _token: '{{csrf_token()}}'
            };

            $.post('{{route('account.update')}}', data, function (res) {
              if (!res) {
                alert('Some error');
                console.log(res);
                $block.find('input').prop('disabled', false);
                $block.data('edit-mode', true);
              }
              if (res) {
                $block.find('.edit-buttons').remove();

                if (field === 'password')
                  $block.find('input').val('');

                if (field === 'name')
                  $('.username').text($block.find('input').val());

                if (field === 'open_ai_token') {
                  $block.find('input').val('********');

                  if(!$('[name=open_ai_model]:checked').val()) {
                    const $input = $('input[name=open_ai_model][value="{{\App\Services\OpenAIService::MODEL_4_PREVIEW}}"]');
                    $input.prop('checked', true);
                    $input.trigger('change');
                  }
                }
              }
            }).fail(function (err) {
              alert('Some error: ' + err.responseJSON.message);
              console.log(err);
              $block.find('input').prop('disabled', false);
              $block.data('edit-mode', true);
            });
          });

          $(document).on('change', '[name=open_ai_model]', function () {
            var data = {
              field: 'open_ai_model',
              value: $(this).val(),
              _token: '{{csrf_token()}}'
            };

            $.post('{{route('account.update')}}', data, function (res) {
              if (!res) {
                alert('Some error');
                console.log(res);
              }
            }).fail(function (err) {
              alert('Some error: ' + err.responseJSON.message);
              console.log(err);
            });
          });
        });
    </script>
<!-- Event snippet for Page view (2) conversion page -->
<script>
  gtag('event', 'conversion', {'send_to': 'AW-11291449055/jhnuCLvezJIZEN-tl4gq'});
</script>

@endpush
