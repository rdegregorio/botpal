@guest
<header class="header">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo Column -->
            <div class="col-md-3 col-6">
                <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                    <span class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #1a1a1a; border-radius: 8px; color: white; font-size: 14px;">
                        <i class="bi bi-chat-dots-fill"></i>
                    </span>
                    <span style="font-weight: 600; font-size: 18px; color: #1a1a1a;">BotPal</span>
                </a>
            </div>
            <!-- Menu Column -->
            <div class="col-md-9 col-6 text-end">
                <a href="{{route('login')}}" class="menu-inactive" title="Login"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                <a href="{{route('register')}}" class="btn btn-primary btn-sm ms-2" title="Register">Get started</a>
            </div>
        </div>
    </div>
</header>

@else
<header class="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 col-6">
                <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                    <span class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #1a1a1a; border-radius: 8px; color: white; font-size: 14px;">
                        <i class="bi bi-chat-dots-fill"></i>
                    </span>
                    <span style="font-weight: 600; font-size: 18px; color: #1a1a1a;">BotPal</span>
                </a>
            </div>
            <div class="col-md-9 col-6 text-md-end">
                <a href="{{route('account.index')}}" class="menu-inactive" title="My Account"><i class="bi bi-person"></i> Account</a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-inactive" title="Logout"><i class="bi bi-box-arrow-right"></i> Logout</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        @if(Auth::user()?->getCurrentActiveSubscription() && !request()->routeIs('main'))
            <div class="text-center top-menu mt-3">
                <a @class(['menu-active' => request()->routeIs('account.index'), 'menu-inactive' => !request()->routeIs('account.index')]) href="/account"><i class="bi bi-person"></i> Account</a>
                <a @class(['menu-active' => request()->routeIs('settings'), 'menu-inactive' => !request()->routeIs('settings')]) href="{{route('settings')}}"><i class="bi bi-gear"></i> Settings</a>
                <a @class(['menu-active' => request()->routeIs('knowledge'), 'menu-inactive' => !request()->routeIs('knowledge')]) href="{{route('knowledge')}}"><i class="bi bi-book"></i> Knowledge</a>
                <a @class(['menu-active' => request()->routeIs('messages'), 'menu-inactive' => !request()->routeIs('messages')]) href="{{route('messages')}}"><i class="bi bi-chat-dots"></i> Messages</a>
                <a @class(['menu-active' => request()->routeIs('preview'), 'menu-inactive' => !request()->routeIs('preview')]) href="{{route('preview')}}"><i class="bi bi-eye"></i> Preview</a>
            </div>
        @endif
    </div>
</header>
@endguest

@if(!request()->routeIs('preview'))
<!-- Start of BotPal Embed Code-->
<script async src="https://iamsam.ai/api/chat/embed/139acdc0-0345-48bc-9e53-f3b733550765"></script>
<!-- End of BotPal Embed Code -->
@endif
