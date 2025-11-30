@php
    /** @var \App\Models\User $user */
    /** @var \App\Models\Subscription $subscription */
@endphp
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
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Edit subscription</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </li>
                            <li><a class="close-link">
                                    <i class="fa fa-close"></i>
                                </a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{action([\App\Http\Controllers\Admin\UsersController::class, 'subscription'], [$user->id, $subscription->id])}}"
                              class="form-horizontal form-label-left"
                              method="post">
                            @csrf

                            @foreach([
                                'type',
                                'stripe_id',
                                'stripe_plan',
                                'ends_at',
                                'trial_requests_count',
                                'requests_count',
                                'custom_price',
                                'trial_ends_at',
                                'expires_at',
                                'created_at',
                                'updated_at',
                                'canceled_at',
                            ] as $f)
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="{{$f}}">
                                        {{$f}}
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="{{$f}}" name="{{$f}}" value="{{ old($f, $subscription->$f) }}"
                                               class="form-control col-md-7 col-xs-12" type="text">
                                        @if($f === 'type')
                                            <br>
                                            Available plans/types:
                                            <br>
                                            @foreach(\App\Models\Subscription::PLAN_NAMES as $k => $v)
                                                {{$k}} - {{$v}}
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="{{$f}}">
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

