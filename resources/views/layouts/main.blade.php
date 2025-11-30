<!DOCTYPE html>
<html lang="en">
@include('layouts.includes._head')
<body>
@include('layouts.includes._header')
@include('layouts.includes._flash-messages')
@yield('content')
@include('layouts.includes._footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@stack('bottom')
@include('includes.subscriptions._upgrade-modal')
</body>
</html>
