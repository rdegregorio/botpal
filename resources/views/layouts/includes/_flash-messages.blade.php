<div class="flash-messages text-center">
    @foreach (['danger', 'warning', 'success', 'info', 'status'] as $msg)
        @if(Session::has($msg))
            <div class="alert alert-{{ $msg === 'status' ? 'info' : $msg }}">
                @if(is_array(Session::get($msg)))
                    <ul>
                        @foreach(Session::get($msg) as $item)
                            <li>{{$item}}</li>
                        @endforeach
                    </ul>
                @else
                    {{ Session::get($msg) }}
                @endif
            </div>
        @endif
    @endforeach

    @if (isset($errors) && count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
