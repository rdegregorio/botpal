<form id="cancel" method="post" action="{{route('account.cancel')}}" class="d-inline-block">
    {{csrf_field()}}
    <button class="btn btn-link btn-sm">Unsubscribe</button>
</form>

@push('bottom')

    <div class="modal fade" style="font-size: 17px;" id="cancel-modal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title">Cancel Subscription</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="step-1">
                        Are you sure you want to cancel your subscription?
                    </div>
                    <div class="step-2" style="display: none;">
                        <p style="font-size: 0.75rem;" class="mb-2">Is there any feedback you mind sharing? We would
                            love to understand what you were expecting, where we failed to deliver, what may have been
                            confusing, etc... We read each one carefully :) </p>

                        <div>
                                    <textarea
                                        style="width: 100%; border-radius: 10px; border: 1px solid grey; padding: 5px 10px;"
                                        class="mr-1"
                                        rows="10"
                                        name="cancel_reason_other"
                                        required></textarea>
                            <p class="help-block mt-0" style="display: none; color: red;"
                               id="cancel_reason_other_error">
                                Please fill the field
                            </p>
                        </div>
                    </div>
                    <div class="step-3" style="display: none;">
                        Your subscription has been cancelled - you won't be billed again. Note that you'll still be able
                        to use the service until it expires.
                        <br>
                        <br>
                        If you change your mind, you can resume your subscription.
                        <br>
                        <br>
                        Thanks!
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-primary btn btn-sm step-1-btn">Continue</button>
                    <button class="btn-danger btn btn-sm step-2-btn" style="display: none;">Cancel Subscription</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function (e) {
            var $cancelModal = $('#cancel-modal');
            var reloadRequired = false;

            $('#cancel').submit(function (e) {
                e.preventDefault();
                $cancelModal.find('.step-1, .step-1-btn').show();
                $cancelModal.find('.step-2, .step-2-btn, #cancel_reason_other_error').hide();
                $cancelModal.modal('show');
            });

            $cancelModal.find('.step-1-btn').click(function () {
                $cancelModal.find('.step-1, .step-1-btn').hide();
                $cancelModal.find('.step-2, .step-2-btn').show();
            });

            $cancelModal.find('.step-2-btn').click(function () {
                var reasonText = $('[name="cancel_reason_other"]').val().trim();

                if (reasonText.length < 3) {
                    $('#cancel_reason_other_error').show();
                    return;
                }

                $.post('{{route('account.cancel')}}', {
                    reason: reasonText,
                    _token: '{{csrf_token()}}'
                }, function (res) {
                    if (res.result) {
                        $cancelModal.find('.modal-footer, .step-1, .step-2').remove();
                        $cancelModal.find('.step-3').show();
                        reloadRequired = true;
                        return;
                    }

                    alert(res.error);
                }).fail(function (e) {
                    alert('Error, please write to support');
                });
            });

            $cancelModal.on('hidden.bs.modal', function () {
                if (reloadRequired) {
                    location.reload();
                }
            });
        });
    </script>
@endpush
