@extends('layouts.default')

@section('content')

<!--begin::Card-->
<div class="card card-custom">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">

                <!--begin::Group-->
                <div class="form-group">
                    <label class="form-label">Filter Patient Type</label>

                    <select class="form-control form-control-lg {{ $errors->has('patient_type') ? 'is-invalid' : '' }} basic-select2"
                        name="patient_type" placeholder="Filter Patient Type">
                        <option value="All">All Patient Types</option>
                        @foreach ($patient_types as $typeKey => $type)
                        <option value="{{ $typeKey }}">{{ $type }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('patient_type'))
                    <span class=" invalid-feedback">{{ $errors->first('patient_type') }}</span>
                    @endif
                </div>
                <!--end::Group-->

            </div>
            <div class="col-md-3">

                <!--begin::Group-->
                <div class="form-group">
                    <label class="form-label">Filter Report Type</label>

                    <select class="form-control form-control-lg {{ $errors->has('report_type') ? 'is-invalid' : '' }} basic-select2"
                        name="report_type" placeholder="Filter Report Type">
                        <option value="All">All Report Types</option>
                        @foreach ($report_types as $typeKey => $type)
                        <option value="{{ $typeKey }}">{{ $type }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('report_type'))
                    <span class=" invalid-feedback">{{ $errors->first('report_type') }}</span>
                    @endif
                </div>
                <!--end::Group-->

            </div>
        </div>
        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
            style="margin-top: 13px !important">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Patient Type</th>
                    <th>Report Type</th>
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

    $(document).ready(function () {

        table = table.DataTable({
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
                        'id',
                        'name',
                        'patient_type',
                        'is_report',
                        'actions'
                    ],
                },
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'patient_type' },
                { data: 'is_report' },
                { data: 'actions', responsivePriority: -1 },
            ],
        });

        $(document).on('change', '[name="patient_type"]', filterData);
        $(document).on('change', '[name="report_type"]', filterData);

        function filterData() {
            var selected_patient_type = $('[name="patient_type"]').val();
            var selected_report_type = $('[name="report_type"]').val();
            table.ajax.url(
                DATATABLE_URL +
                '?patient_type=' + selected_patient_type +
                '&report_type=' + selected_report_type
            ).load();
        }
    });
</script>

@endsection
