@php
    $subscribe = $subscribe ?? true;
@endphp
@push('bottom')
    <div class="modal fade" style="font-size: 17px;" id="business-subscribe-unsubscribe-modal" tabindex="-1"
         role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title">Contact your Account Manager</strong>
                </div>
                <div class="modal-body">
                    Please contact your account manager: ricardo@stocknewsapi.com to {{$subscribe ? 'subscribe' : 'unsubscribe'}}.
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.btn-business-subscribe-unsubscribe-modal').click(function (e) {
            $('#business-subscribe-unsubscribe-modal').modal('show');
        });
    </script>
@endpush
