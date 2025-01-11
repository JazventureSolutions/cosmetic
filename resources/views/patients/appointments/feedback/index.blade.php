@extends('layouts.default')

@section('content')

@php
    $array = [
        ["name" => "Environment / Hygiene of the clinic", "slug" => "environment_hygiene_clinic", "options" => ["Very Satisfied", "Satisfied", "Dissatisfied", "Very Dissatisfied"]],
        ["name" => "Attitude of the staff", "slug" => "attitude_staff", "options" => ["Very Satisfied", "Satisfied", "Dissatisfied", "Very Dissatisfied"]],
        ["name" => "Pre-op Counselling and consultation", "slug" => "pre_op_counselling_consultation", "options" => ["Very Satisfied", "Satisfied", "Dissatisfied", "Very Dissatisfied"]],
        ["name" => "Doctor’s conduct and expertise", "slug" => "doctors_conduct_expertise", "options" => ["Very Satisfied", "Satisfied", "Dissatisfied", "Very Dissatisfied"]],
        ["name" => "Post-operative after care advice", "slug" => "post_operative_care_advice", "options" => ["Very Satisfied", "Satisfied", "Dissatisfied", "Very Dissatisfied"]],
        ["name" => "Outcome / result of the procedure", "slug" => "outcome_result_procedure", "options" => ["Very Satisfied", "Satisfied", "Dissatisfied", "Very Dissatisfied"]],
        ["name" => "Efficacy of the post-operative follow up", "slug" => "efficacy_post_operative_follow", "options" => ["Very Satisfied", "Satisfied", "Dissatisfied", "Very Dissatisfied"]],
        ["name" => "Would you recommend this service?", "slug" => "recommend", "options" => ["Yes", "No"]],
    ];
@endphp

<div class="row g-5 g-xl-8">
    @foreach ($array as $arrData)
    <!--begin::Col-->
    <div class="col-xl-4">
        <!--begin::Mixed Widget 14-->
        <div class="card card-xxl-stretch mb-5 mb-xl-8" style="background-color: #F7D9E3">
            <!--begin::Body-->
            <div class="card-body d-flex flex-column">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column mb-7">
                    <!--begin::Title-->
                    <span class="text-dark text-hover-primary fw-bolder fs-3" style="font-size: 18px;">{{ $arrData['name'] }}</span>
                    <!--end::Title-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Row-->
                <div class="row g-0">
                    @foreach ($arrData["options"] as $opt)
                    <!--begin::Col-->
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-9 me-2">
                            <!--begin::Title-->
                            <div>
                                @php
                                    $count = $data[$arrData['slug']]->where($arrData['slug'], $opt)->first();
                                    $count = $count ? $count->count : 0;
                                @endphp
                                <div class="fs-5 text-dark fw-bolder lh-1">{{ $count }}</div>
                                <div class="fs-7 text-gray-600 fw-bold">{{ $opt }}</div>
                            </div>
                            <!--end::Title-->
                        </div>
                    </div>
                    <!--end::Col-->
                    @endforeach
                </div>
                <!--end::Row-->
            </div>
        </div>
        <!--end::Mixed Widget 14-->
    </div>
    <!--end::Col-->
    @endforeach
</div>

<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            {{-- <h3 class="card-label">Remote Datasource --}}
            {{-- <span class="d-block text-muted pt-2 font-size-sm">Sorting &amp; pagination remote datasource</span> --}}
            {{-- </h3> --}}
        </div>
        <div class="card-toolbar">
            <div class="form-group mb-0 mr-2">
                <input
                    class="form-control form-control-lg {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                    type="date" name="start_date" placeholder="start date"
                    value="{{ old('start_date', \Carbon\Carbon::now()->toDateString()) }}" />
            </div>

            <div class="form-group mb-0 mr-2">
                <input
                    class="form-control form-control-lg {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                    type="date" name="end_date" placeholder="end date"
                    value="{{ old('end_date', \Carbon\Carbon::now()->toDateString()) }}" />
            </div>

            <div class="form-group mb-0 mr-2">
                <select class="form-control form-control-lg {{ $errors->has('type') ? 'is-invalid' : '' }} basic-select2" name="branch_id" placeholder="Select Branch">
                    <option value="">Select Branch</option>
                    @foreach ($branches as $branchKey => $branch)
                    <option value="{{ $branchKey }}" {{ (old('branch_id', Auth::user()->branch_id) == $branchKey) ? 'selected' : '' }}>{{ $branch }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="summary_datatable"
            style="margin-top: 13px !important">
            <thead>
                <tr>
                    <th>Feedback</th>
                    <th>Very Satisfied</th>
                    <th>Satisfied</th>
                    <th>Dissatisfied</th>
                    <th>Very Dissatisfied</th>
                </tr>
            </thead>
        </table>
        <!--end: Datatable-->
    </div>
    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="list_datatable"
            style="margin-top: 13px !important">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Environment / Hygiene of the clinic</th>
                    <th>Attitude of the staff</th>
                    <th>Pre-op Counselling and consultation</th>
                    <th>Doctor’s conduct and expertise</th>
                    <th>Post-operative after care advice</th>
                    <th>Outcome / result of the procedure</th>
                    <th>Efficacy of the post-operative follow up</th>
                    <th>Remarks</th>
                    <th>Recommend</th>
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

{{-- <link href="{{ url('/') }}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" /> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">

<style>
    .app-canceled {
        background-color: rgba(255,0,0,0.2) !important;
    }
</style>

@endsection


@section('scripts')

<script>
    var DATATABLE_URL = '{{ url()->current() }}';
    var DATATABLE2_URL = '{{ route("patients.appointments.feedback.summary") }}';
    var CSRF_TOKEN = '{{ csrf_token() }}';
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.2/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.2/vfs_fonts.js"></script>
{{-- <script src="{{ url('/') }}/assets/plugins/custom/datatables/datatables.bundle.js"></script> --}}
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="{{ url('/') }}/assets/js/pages/widgets.js"></script>

<script>
    $(document).ready(function () {
        var table1 = $('#list_datatable');
        var table2 = $('#summary_datatable');

        // begin first table
        table1 = table1.DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ordering: true,
            // order: [[ 5, "asc" ]],
            createdRow: function(row, data, dataIndex) {
                if (data.status == "canceled") {
                    $(row).addClass('app-canceled');
                }
            },
            ajax: {
                url: DATATABLE_URL,
                type: 'GET',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'id',
                        'name',
                        "environment_hygiene_clinic",
                        "attitude_staff",
                        "pre_op_counselling_consultation",
                        "doctors_conduct_expertise",
                        "post_operative_care_advice",
                        "outcome_result_procedure",
                        "efficacy_post_operative_follow",
                        "remarks",
                        "recommend",
                        'actions',
                    ],
                },
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'environment_hygiene_clinic' },
                { data: 'attitude_staff' },
                { data: 'pre_op_counselling_consultation' },
                { data: 'doctors_conduct_expertise' },
                { data: 'post_operative_care_advice' },
                { data: 'outcome_result_procedure' },
                { data: 'efficacy_post_operative_follow' },
                { data: "remarks" },
                { data: "recommend" },
                { data: 'actions', responsivePriority: -1 },
            ],
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ]
        });

        table2 = table2.DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ordering: false,
            // order: [[ 5, "asc" ]],
            createdRow: function(row, data, dataIndex) {
                if (data.status == "canceled") {
                    $(row).addClass('app-canceled');
                }
            },
            ajax: {
                url: DATATABLE2_URL,
                type: 'GET',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        // 'id',
                        'name',
                        "Very Satisfied",
                        "Satisfied",
                        "Dissatisfied",
                        "Very Dissatisfied"
                    ],
                },
            },
            columns: [
                // { data: 'id' },
                { data: 'name' },
                { data: "Very Satisfied" },
                { data: "Satisfied" },
                { data: "Dissatisfied" },
                { data: "Very Dissatisfied" }
            ],
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ]
        });

        var branch_id = $('[name="branch_id"]').val();
        var start_date = $('[name="start_date"]').val();
        var end_date = $('[name="end_date"]').val();

        function reloadTable() {
            branch_id = $('[name="branch_id"]').val();
            start_date = $('[name="start_date"]').val();
            end_date = $('[name="end_date"]').val();

            table1.ajax.url(
                DATATABLE_URL +
                '?branch_id=' + branch_id +
                '&start_date=' + start_date +
                '&end_date=' + end_date
            ).load();

            table2.ajax.url(
                DATATABLE2_URL +
                '?branch_id=' + branch_id +
                '&start_date=' + start_date +
                '&end_date=' + end_date
            ).load();
        }

        $(document).on('change', '[name="branch_id"]', reloadTable);
        $(document).on('change', '[name="start_date"]', reloadTable);
        $(document).on('change', '[name="end_date"]', reloadTable);

        reloadTable();
    });
</script>

@endsection
