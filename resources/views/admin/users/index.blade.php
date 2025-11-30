@extends('admin.admin')

@section('content')
    <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 py-4 bg-light">
        <div class="page-title">
            <div class="title_left">
                <h3></h3>
            </div>

        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 style="">Users list</h2>
                        <div class="ms-auto">
                            <a href="{{action([\App\Http\Controllers\Admin\UsersController::class, 'index'])}}?not-verified=1" class="btn btn-sm btn-outline-custom {{request()->input('not-verified') ? '' : 'active'}}">Pre-Registered</a>
                            <a href="{{action([\App\Http\Controllers\Admin\UsersController::class, 'index'])}}" class="btn btn-sm btn-outline-custom {{ ! request()->input('not-verified') ? '' : 'active'}}">Registered</a>
                        </div>
                        <span id="business-plan-create" class="btn btn-sm btn-outline-custom active">
                            Create Business
                        </span>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row text-center">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th data-sort="id">ID</th>
                                    <th data-sort="name">Name</th>
                                    <th data-sort="email">Email</th>
                                    <th data-sort="type">Subscription Plan</th>
                                    <th data-sort="stripe_id">Cus_ID</th>
                                    <th data-sort="expires_at">Expires At</th>
                                    <th data-sort="price">Monthly Price</th>
                                    <th data-sort="knowledgebase">Knowledgebase</th>
                                    <th class="no-border"></th>
                                </tr>
                                </thead>
                                <tbody id="items">
                                @include('admin.users._item-rows')
                                </tbody>
                            </table>

                            <div class="text-center">
                                {{$users->appends(compact('sort', 'order'))->render()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modal-business-plan-create">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="business-plan-create-form"
                      action="{{action([\App\Http\Controllers\Admin\UsersController::class, 'createBusinessSubscription'])}}"
                      class="form-horizontal form-label-left" method="post">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title">Business Plan</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <p class="help-block mt-0">
                            You can create a new user or update an existing user.
                            <br>
                            If a user with the same email exists it will be updated else a new user will be created.
                            <br>
                            If a user exists than the password and name fields will be ignored.
                        </p>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                Name
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="name" value="{{ old('name') }}"
                                       name="name" required="required"
                                       class="form-control col-md-7 col-xs-12" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">
                                Email
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="email" value="{{ old('email') }}"
                                       name="email" required="required"
                                       class="form-control col-md-7 col-xs-12" type="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">
                                Password
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="password" value="{{ old('password') }}"
                                       name="password"
                                       class="form-control col-md-7 col-xs-12" type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="stripe_id">
                                Stripe ID
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="stripe_id" value="{{ old('stripe_id') }}"
                                       name="stripe_id"
                                       class="form-control col-md-7 col-xs-12" type="text">
                            </div>
                        </div>
                        <hr>
                        <h4>Subscription Details</h4>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="custom_available_requests">
                                Custom Available Requests
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="custom_available_requests" value="{{ old('limit') }}"
                                       name="limit" required="required"
                                       class="form-control col-md-7 col-xs-12" type="text">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="custom_available_requests">
                                Custom Price
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="custom_price" value="{{ old('price') }}"
                                       name="price" required="required"
                                       class="form-control col-md-7 col-xs-12" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="modal-cancel">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form id="cancel-form"
                      action="{{action([\App\Http\Controllers\Admin\UsersController::class, 'createBusinessSubscription'])}}"
                      class="form-horizontal form-label-left" method="post">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title">Email</h4>
                    </div>
                    <div class="modal-body" style="display:flex; justify-content: space-around;">
                        @csrf
                        <button type="button" onclick="window.cancelSubscription(1)" class="btn btn-sm view-btn active">
                            Send Cancel Email
                        </button>
                        <button type="button" onclick="window.cancelSubscription(0)" class="btn btn-sm view-btn">No
                            Email
                        </button>
                    </div>
                    <div class="modal-footer" style="text-align: left !important;">
                        Are you sure you want to cancel the user subscription in database?
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="modal-notify-failed-payment">
        <div class="modal-dialog">
            <div class="modal-content">
                <form
                      action="#"
                      class="form-horizontal form-label-left" method="post">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title">Email</h4>
                    </div>
                    <div class="modal-body" style="display:flex; justify-content: center;">
                        @csrf
                    </div>
                    <div id="notify-failed-payment-result" class="text-center"></div>
                    <div class="modal-footer" style="text-align: center !important;">
                        Are you sure you want to send a notification about a missing payment?
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <style>
        .view-btn {
            padding-left: 15px !important;
            padding-right: 15px !important;
            border-radius: 7px;
            color: #fff;
            background: #878696 !important;
        }

        .view-btn.active {
            background: #2991ff !important;
        }

        tr:hover .delete-user {
            display: inline-block !important;
        }

        td span {
            line-height: 1.2;
        }

        #business-plan-create {
            font-weight: bold;
            color: #1273eb;
            cursor: pointer;
        }

        .token-disabled {
            color: orange;
            font-weight: bold;
        }
    </style>
@endsection

@push('scripts')
    <div class="text-center">
        <button data-page="1" id="load-more" class="btn btn-primary hidden">Load more</button>
    </div>
    <script type="text/javascript">

        var $loadMoreBtn = $('#load-more');
        var setDataAsFresh = false;
        var lastPage = $('.pagination .page-item:eq(-2)').text();
        $('.pagination').hide();

        $loadMoreBtn.click(function () {
            var $btn = $(this);
            var page = $btn.data('page') + 1;

            if (setDataAsFresh) {
                page = 1;
            } else {
                if (page > lastPage) {
                    return;
                }
            }

            var data = {
                sort: '{{request()->input('sort', 'id')}}',
                order: '{{request()->input('order', 0)}}',
                'not-verified': '{{request()->input('not-verified', 0)}}',
                page: page
            };

            var url = $btn.data('url');

            $.get(url, data).success(function (res) {
                if (setDataAsFresh) {
                    setDataAsFresh = false;
                    $('#items').html(res.html);
                    lastPage = res.lastPage;
                } else {
                    $('#items').append(res.html);
                }

                $btn.data('page', page);

                $(document).scroll(function () {
                    if (element_in_scroll($loadMoreBtn, $(document))) {
                        $(document).unbind('scroll');
                        $loadMoreBtn.trigger('click');
                    }
                });
            });
        });

        function element_in_scroll(elem, wrapper) {
            return elem[0].offsetTop <= wrapper.scrollTop() + 1050;
        }

        $(document).scroll(function () {
            if (element_in_scroll($loadMoreBtn, $(document))) {
                $(document).unbind('scroll');
                $loadMoreBtn.trigger('click');
            }
        });

    </script>

    <script>
        (function (e) {
            var $modal = $('#modal-business-plan-create');

            $(document).on('click', '#business-plan-create', function () {
                $modal.modal('show');
            });
        })();
    </script>

    <script>

        var sort = '{{$sort}}';
        var order = {{$order}};

        $('[data-sort]').each(function () {
            var $item = $(this);

            if ($item.data('sort') !== sort) {
                return
            }

            var way = order ? 'up' : 'down';
            var arrowHtml = ' <span class="fa fa-chevron-' + way + '"></span>'
            $item.append(arrowHtml);
        });

        $(document).on('click', '[data-sort]', function () {
            var sortField = $(this).data('sort');
            var orderVal = +(sort === sortField && !order);

            var url = '{{action([\App\Http\Controllers\Admin\UsersController::class, 'index'])}}?sort=' + sortField + '&order=' + orderVal;
            location.href = url;
        });

        $(document).on('click', '.delete-user', function () {
            var self = $(this);
            var userId = self.attr('userId');
            var name = self.parent().siblings('.name').text();
            if (confirm('Are you sure you want to permanently delete user ' + name +
                ' and all related data?')) {
                self.siblings('.glyphicon').show();
                self.hide();

                var url = '{{action([\App\Http\Controllers\Admin\UsersController::class, 'delete'])}}/' + userId;

                $.post(url).success(function (data) {
                    console.log(data);
                    if (data.result) {
                        self.parent().parent().remove();
                    } else {
                        alert('error');
                    }
                });
            }
        });


        (function (e) {
            var $cancelBtn;
            var $modal = $('#modal-cancel');

            $(document).on('click', '.cancel-user-subscription', function () {
                $modal.modal('show');
                $cancelBtn = $(this);
            });

            window.cancelSubscription = (send_email) => {

                $modal.hide();

                var userId = $cancelBtn.attr('userId');
                $cancelBtn.siblings('.glyphicon').show();
                $cancelBtn.hide();

                var url = '{{action([\App\Http\Controllers\Admin\UsersController::class, 'cancelSubscriptionInDatabase'])}}/' + userId;

                $.post(url, {send_email}).success(function (data) {
                    console.log(data);
                    if (data.result) {
                        location.reload();
                    } else {
                        alert('error');
                    }
                });
            }
        })();

        (function (e) {
            var $notifyFailedBtn;
            var $modal = $('#modal-notify-failed-payment');
            var $result = $('#notify-failed-payment-result');

            $(document).on('click', '.notify-user-missing-payment', function () {
                $result.html('');
                $modal.modal('show');
                $notifyFailedBtn = $(this);
            });

            window.notifyUser = (type) => {

            }
        })();

        $('.switch-plan').click(function () {
            var select = '<select class="form-control change-subscription-type">' +
                '<option value="{{\App\Models\Subscription::PLAN_BUSINESS}}">' +
                '    {{\App\Models\Subscription::getNameByType(\App\Models\Subscription::PLAN_BUSINESS)}}' +
                '</option>' +
                '</select>';

            var $btn = $(this);
            var $planName = $btn.closest('tr').find('.plan-name');

            $planName.html(select);

            $btn.hide();

            $btn.closest('tr').find('.subscription-actions').removeClass('hidden');
        });

        $(document).on('click', '.action-cancel', function () {
            var $row = $(this).closest('tr');

            $row.find('[data-val]').each(function (item) {
                item = $(this);
                item.html(item.data('val'));
            });

            $row.find('.fa-caret-down').show();

            $(this).parent().addClass('hidden');
        });

        $(document).on('click', '.action-save', function () {
            var $row = $(this).closest('tr');
            var $btn = $(this);

            var type = parseInt($row.find('.change-subscription-type').val());
            var limit = parseInt($row.find('.limit-val input').val()) || null;
            var price = parseInt($row.find('.price-val input').val()) || null;

            var data = {
                user_id: $row.data('user'),
                type: type,
                limit: limit,
                price: price,
            };

            $.post('{{action([\App\Http\Controllers\Admin\UsersController::class, 'setSubscription'])}}', data).success(function (res) {
                if (res.success) {
                    $row.find('.plan-name').html($row.find('.change-subscription-type option:selected').text());

                    if (type === {{\App\Models\Subscription::PLAN_BUSINESS}}) {
                        $row.find('.limit-val').html(limit);
                        $row.find('.price-val').html('$' + price);
                    }

                    $row.find('.fa-caret-down').show();

                    $btn.parent().addClass('hidden');
                } else {
                    alert('Error');
                }
            });
        });

        $(document).on('change', '.change-subscription-type', function () {
            var $select = $(this);
            var $row = $select.closest('tr');


            if ($select.val() === '{{\App\Models\Subscription::PLAN_BUSINESS}}') {
                $row.find('.limit-val').html('<input type="text" class="form-control" placeholder="Place integer value">');
                $row.find('.price-val').html('<input type="text" class="form-control" placeholder="Place integer value">');
            }
        });
    </script>
@endpush
