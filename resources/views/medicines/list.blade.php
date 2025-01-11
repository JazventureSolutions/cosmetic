@extends('layouts.default')

@section('content')

<!--begin::Card-->
<div class="card card-custom">
    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
            style="margin-top: 13px !important">
            <thead>
                <tr>
                    {{-- <th>ID</th> --}}
                    <th>Name</th>
                    <th>Batch</th>
                    <th>Expiry</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->

@endsection


@section('styles')

<link href="{{ url('/') }}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

@endsection


@section('scripts')

<script>
    var DATATABLE_URL = '{{ url()->current() }}';
</script>

<script src="{{ url('/') }}/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ url('/') }}/assets/js/pages/widgets.js"></script>

<script>
    var table = $('#kt_datatable');

    // begin first table
    table.DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: true,
        order: [[ 1, "asc" ]],
        ajax: {
            url: DATATABLE_URL,
            type: 'GET',
            data: {
                // parameters for custom backend script demo
                columnsDef: [
                    // 'id',
                    'name',
                    'batch',
                    'expiry',
                    'actions'
                ],
            },
        },
        columns: [
            // { data: 'id' },
            { data: 'name' },
            { data: 'batch' },
            { data: 'expiry' },
            { data: 'actions', responsivePriority: -1 },
        ],
    });
</script>

@endsection
