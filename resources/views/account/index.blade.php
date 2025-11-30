@extends('layouts.dashboard')

@section('page-title', 'Profile')

@section('content')
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title">Profile Information</h2>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-group mb-4">
                    <label class="form-label">Name</label>
                    <div data-editable>
                        <input type="text" readonly class="form-control" name="name" id="name"
                               value="{{Auth::user()->name}}">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Email</label>
                    <div data-editable>
                        <input type="email" readonly class="form-control" name="email" id="email"
                               value="{{Auth::user()->email}}">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Password</label>
                    <div data-editable>
                        <input type="password" readonly class="form-control" name="password" id="password"
                               placeholder="••••••••">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-card" style="border-color: #fee2e2;">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title" style="color: #dc2626;">Danger Zone</h2>
        </div>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            Once you delete your account, there is no going back. Please be certain.
        </p>
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            Delete Account
        </button>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('account.delete-account') }}">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                        <p class="text-muted small">All your data, including chatbot configurations, knowledge base, and messages will be permanently deleted.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <script>
        $(function() {
            $('input:read-only').click(function () {
                $(this).closest('[data-editable]')?.find('[data-edit]')?.click();
            });

            $('[data-editable]').mouseover(function () {
                if ($(this).find('.edit-buttons').length) return;
                var $buttons = $('<div class="edit-buttons"><button data-edit class="btn btn-sm btn-primary">Edit</button></div>');
                $(this).append($buttons);
            });

            $('[data-editable]').mouseleave(function () {
                if ($(this).data('edit-mode')) return;
                $(this).find('.edit-buttons').remove();
            });

            $(document).on('click', '[data-editable] [data-edit]', function () {
                var $block = $(this).closest('[data-editable]');
                $block.data('edit-mode', true);
                $block.find('input').prop('readonly', false).focus();
                var buttons = '<button data-save class="btn btn-sm btn-success">Save</button> ' +
                    '<button data-cancel class="btn btn-sm btn-secondary">Cancel</button>';
                $block.find('.edit-buttons').html(buttons);
            });

            $(document).on('click', '[data-editable] [data-cancel]', function () {
                var $block = $(this).closest('[data-editable]');
                $block.data('edit-mode', false);
                $block.find('input').prop('readonly', true);
                $block.find('.edit-buttons').remove();
            });

            $(document).on('click', '[data-editable] [data-save]', function () {
                var $block = $(this).closest('[data-editable]');
                $block.data('edit-mode', false);
                $block.find('input').prop('readonly', true);

                var field = $block.find('input').attr('name');
                var data = {
                    field: field,
                    value: $block.find('input').val(),
                    _token: '{{csrf_token()}}'
                };

                $.post('{{route('account.update')}}', data, function (res) {
                    if (res) {
                        $block.find('.edit-buttons').remove();
                        if (field === 'password') $block.find('input').val('');
                        if (field === 'name') $('.sidebar-user-name').text($block.find('input').val());
                    }
                }).fail(function (err) {
                    alert('Error: ' + (err.responseJSON?.message || 'Something went wrong'));
                    $block.find('input').prop('readonly', false);
                    $block.data('edit-mode', true);
                });
            });
        });
    </script>
@endpush
