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
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Cancelled Subscriptions Logs</h2>
                        <button class="btn-primary btn pull-right" id="delete-logs">Delete Logs</button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <pre>{{$log}}</pre>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $('#delete-logs').click(function () {
            $.post('{{request()->url()}}', {'_token': '{{csrf_token()}}'},
                function (res) {
                    location.reload();
                });
        });
    </script>
@endpush
