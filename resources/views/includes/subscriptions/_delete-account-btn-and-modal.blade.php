@if(!request()->routeIs('pages.subscribe'))
    <div id="delete-account" class="d-inline-block">
        <button type="button" class="btn btn-link btn-sm">Delete Account</button>
    </div>
@endif

@push('bottom')

    <div class="modal fade" style="font-size: 17px;" id="delete-account-modal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title">Delete Account</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="step-1">
                        <p>Are you sure you want to delete your account? This will delete all of your data such as: user access, api token call log and more...</p>

                        <p class="m-0">You will lose access to your account once confirmed.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-danger btn btn-sm" id="delete-account-confirmation">Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function (e) {
            var $modal = $('#delete-account-modal');

            $('#delete-account .btn').click(function (e) {
                e.preventDefault();
                $modal.modal('show');
            });

            $modal.find('#delete-account-confirmation').click(function () {
                $.post('{{route('account.delete-account')}}', {
                    _token: '{{csrf_token()}}'
                }, function (res) {
                    if (res.result) {
                        location.href = '/';
                        return;
                    }

                    alert(res.error);
                }).fail(function (e) {
                    alert('Error, please write to support');
                });
            });
        });
    </script>
@endpush
