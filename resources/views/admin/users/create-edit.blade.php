@php
    /** @var \App\Models\User $user */
    /** @var \App\Models\Subscription $s */
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
                        <h2>Edit user</h2>
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
                        <form action="{{action([\App\Http\Controllers\Admin\UsersController::class, 'edit'], $user->id)}}"
                              class="form-horizontal form-label-left"
                              method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                    Name
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input disabled id="name" value="{{ old('name', $user->name) }}"
                                           class="form-control col-md-7 col-xs-12" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">
                                    Email
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" name="email" value="{{ old('email', $user->email) }}"
                                           class="form-control col-md-7 col-xs-12" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="stripe_id">
                                    Customer id
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input autocomplete="off" id="cus_id"
                                           value="{{ old('stripe_id', $user->stripe_id) }}" name="cus_id"
                                             class="form-control col-md-7 col-xs-12" type="text">
                                </div>
                            </div>


                            @if($s)
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sub_id">
                                        Subscription id
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input autocomplete="off" id="sub_id" value="{{ old('sub_id', $s->stripe_id) }}"
                                               name="sub_id"  class="form-control col-md-7 col-xs-12"
                                               type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expires_at">
                                        Expires at
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input autocomplete="off" id="expires_at"
                                               value="{{ old('expires_at', $s->expires_at) }}" name="expires_at"
                                               required="required" class="form-control col-md-7 col-xs-12" type="text"
                                               placeholder="2019-11-18 21:32:54">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="trial_ends_at">
                                        Global user trial_ends_at
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input autocomplete="off" id="trial_ends_at"
                                               value="{{ old('trial_ends_at', $user->trial_ends_at) }}" name="trial_ends_at"
                                               required="required" class="form-control col-md-7 col-xs-12" type="text"
                                               placeholder="2019-11-18 21:32:54">
                                    </div>
                                </div>

                                @php
                                    $price = old('custom_price', ! $s->isBusiness() ? $s->getPrice() * 100 : $s->custom_price);
                                @endphp
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="custom_price">
                                        Price in cents
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input @if( ! $s->isBusiness()) disabled @endif autocomplete="off" id="custom_price"
                                               value="{{ $price }}" name="custom_price" required="required"
                                               class="form-control col-md-7 col-xs-12" type="text" placeholder="In cents">
                                    </div>
                                </div>
                            @else
                                <h3 style="text-align: center; margin-top: 10px;">No Active Subscriptions</h3>
                            @endif
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 mt-2 col-md-offset-4">
                                    <button class="btn btn-primary" type="reset">Reset</button>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>

                        <h3 class="mt-3">Subscriptions</h3>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>type</th>
                                    <th>stripe_id</th>
                                    <th>stripe_plan</th>
                                    <th>trial_ends_at</th>
                                    <th>created_at</th>
                                    <th>ends_at</th>
                                    <th>expires_at</th>
                                    <th>updated_at</th>
                                    <th>trial_requests_count</th>
                                    <th>requests_count</th>
                                    <th>custom_available_requests</th>
                                    <th>custom_available_trial_requests</th>
                                    <th>custom_price</th>
                                    <th>Edit</th>
                                </tr>
                                </thead>
                                <tbody id="items">
                                @foreach($user->subscriptions->reverse() as $subscription)
                                    <tr
                                        @if(optional($s)->id === $subscription->id) style="background: #ddffe9;" @endif
                                        @if($subscription->ends_at) style="background: #ffd6d6;" @endif
                                    >
                                        <td>{{$subscription->id}}</td>
                                        <td>{{$subscription->getName()}}</td>
                                        <td>{{$subscription->stripe_id}}</td>
                                        <td>{{$subscription->stripe_plan}}</td>
                                        <td>{{$subscription->trial_ends_at}}</td>
                                        <td>{{$subscription->created_at}}</td>
                                        <td>{{$subscription->ends_at}}</td>
                                        <td>{{$subscription->expires_at}}</td>
                                        <td>{{$subscription->updated_at}}</td>
                                        <td>{{$subscription->trial_requests_count}}</td>
                                        <td>{{$subscription->requests_count}}</td>
                                        <td>{{$subscription->custom_available_requests}}</td>
                                        <td>{{$subscription->custom_available_trial_requests}}</td>
                                        <td>{{$subscription->custom_price}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{action([\App\Http\Controllers\Admin\UsersController::class, 'subscription'], [$user->id, $subscription->id])}}">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <h3>Chat config</h3>
                        @if($user->chatConfigLatest)
                            <h4>General prompt</h4>
                            <textarea style="width: 100%; height: 100px">{!! htmlspecialchars($user->chatConfigLatest->general_prompt) !!}</textarea>
                            <h4>Welcome message</h4>
                            <textarea style="width: 100%; height: 100px">{!! htmlspecialchars($user->chatConfigLatest->welcome_message) !!}</textarea>
                            <h4>Context ({{$user->chatConfigLatest->type}})</h4>
                            <textarea style="width: 100%; height: 300px">{!! htmlspecialchars($user->chatConfigLatest->getContext()) !!}</textarea>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

