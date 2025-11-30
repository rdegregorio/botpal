@foreach($users as $user)
    @php
        /** @var \App\Models\User $user */
        $s = $user->getCurrentActiveSubscription();

        $noticeColor = null;
    @endphp
    <tr data-user="{{ $user->id }}"
        @if($noticeColor)
            style="background-color: {{$noticeColor}}"
        @endif
    >
        <td>{{ $user->id }}</td>
        <td class="name">{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            @if($s)
                <span class="plan-name"
                      data-val="{{ $s->getName() }}">{{ $s->getName() }}</span>
                @if($s->isBusiness())
                    <div style="padding-left: 15px; display: inline-block;">
                        <i class="fa fa-caret-down switch-plan" aria-hidden="true"></i>
                    </div>
                @endif
            @elseif($user->subscriptions->isNotEmpty())
                @foreach($user->subscriptions as $endedS)
                    <span title="{{$endedS->getName()}}; Ended at: {{$endedS->ends_at}}">Cancelled</span>
                @endforeach
            @endif
        </td>
        <td>
            @if($user->stripe_id)
                <a href="https://dashboard.stripe.com/customers/{{ $user->stripe_id }}" target="_blank">{{ $user->stripe_id }}</a>
            @endif
        </td>
        <td>{{ optional(optional($s)->expires_at)->format('Y-m-d H:i:s') }}</td>
        <td class="price-val" data-val="${{ optional($s)->getPrice() }}">
            ${{ optional($s)->getPrice() }}</td>
        <td class="knowledgebase-val">{{$user->chatConfigLatest?->type}}</td>
        <td class="no-border">
            <a href="{{action([\App\Http\Controllers\Admin\UsersController::class, 'edit'], $user->id)}}">
                <i class="glyphicon glyphicon-edit"></i>
            </a>

            @if($user->id !== Auth::id())
                 
                <!-- Edit icon -->

                <a style="text-decoration: none;" href="{{action([\App\Http\Controllers\Admin\UsersController::class, 'edit'], $user->id)}}">
                    <i class="bi bi-pencil-square text-primary"></i>
                </a>
                <!-- Cancel icon -->
                <i userId="{{ $user->id }}" class="glyphicon glyphicon-ban-circle cancel-user-subscription" style="cursor: pointer;">
                    <i class="bi bi-slash-circle text-warning"></i>
                </i>
                <!-- Delete icon -->
                <i userId="{{ $user->id }}" class="glyphicon glyphicon-trash delete-user" style="cursor: pointer;">
                    <i class="bi bi-trash text-danger"></i>
                </i>

                <span class="glyphicon glyphicon-refresh refresh-animate gray" style="display: none; font-size: 12px;"></span>
            @endif

            <i userId="{{ $user->id }}" class="glyphicon glyphicon-warning-sign toggle-user-token"></i>

            <div class="subscription-actions hidden">
                <i class="fa fa-check action-save"></i>
                <i class="fa fa-remove action-cancel"></i>
            </div>
        </td>
    </tr>
@endforeach
