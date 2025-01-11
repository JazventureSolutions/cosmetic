@extends('layouts.default')

@section('content')

<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            {{-- <h3 class="card-label">Remote Datasource --}}
                {{-- <span class="d-block text-muted pt-2 font-size-sm">Sorting &amp; pagination remote
                    datasource</span> --}}
                {{-- </h3> --}}
        </div>
        <div class="card-toolbar">

            <!--begin::Button-->
            <button id="doUnAttended" class="btn btn-primary font-weight-bolder mr-2">
                Mark Selected As UnAttended
            </button>
            <!--end::Button-->

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


            {{-- <div class="form-group mb-0 mr-2">
                <input class="form-control form-control-lg {{ $errors->has('date') ? 'is-invalid' : '' }}" type="date"
                    name="date" placeholder="Filter date"
                    value="{{ old('date', \Carbon\Carbon::now()->toDateString()) }}" />
            </div> --}}

            <div class="form-group mb-0 mr-2">
                <select
                    class="form-control form-control-lg {{ $errors->has('type') ? 'is-invalid' : '' }} basic-select2"
                    name="branch_id" placeholder="Select Branch">
                    <option value="">Select Branch</option>
                    @foreach ($branches as $branchKey => $branch)
                    <option value="{{ $branchKey }}" {{ (old('branch_id', Auth::user()->branch_id) == $branchKey) ?
                        'selected' : '' }}>{{ $branch }}</option>
                    @endforeach
                </select>
            </div>

            <!--begin::Button-->
            <a href="{{ route('patients.add') }}" class="btn btn-primary font-weight-bolder">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <circle fill="#000000" cx="9" cy="15" r="6" />
                            <path
                                d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>New Record
            </a>
            <!--end::Button-->
        </div>
    </div>
    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
            style="margin-top: 13px !important">
            <thead>
                <tr>
                    <th><input type="checkbox" name="select_all" id="select_all"> ID</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Parents Name</th>
                    <th>GP Details</th>
                    <th>F/U Date</th>
                    <th>Price</th>
                    <th>Contact Details</th>
                    <th>Status</th>
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
        background-color: rgba(255, 0, 0, 0.2) !important;
    }
</style>

@endsection


@section('scripts')

<script>
    var DATATABLE_URL = '{{ url()->current() }}';
    var FOLLOWUP_STATUS_CHANGE_URL = '{{ route("patients.appointments.followupStatusChange") }}';
    var SEND_PAYMENT_MESSAGE_AJAX_URL = '{{ route("patients.send-payment-message") }}';
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
        var table = $('#kt_datatable');

        // begin first table
        table = table.DataTable({
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
                url: DATATABLE_URL,
                type: 'GET',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'id',
                        'name',
                        'date_of_birth',
                        'parents_name',
                        'gp_details',
                        'followup_date',
                        'fees',
                        'contact_details',
                        'status',
                        'actions'
                    ],
                },
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'date_of_birth' },
                { data: 'parents_name' },
                { data: 'gp_details' },
                { data: 'followup_date' },
                { data: 'fees' },
                { data: 'contact_details' },
                { data: 'status' },
                { data: 'actions', responsivePriority: -1 },
            ],
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ]
        });

        $(document).on('click', '.btn-send-payment-message', function () {
            var patientId = $(this).data('patient-id');

            blockPage();

            $.ajax({
                type: 'POST',
                url: SEND_PAYMENT_MESSAGE_AJAX_URL,
                data: {
                    patient_id: patientId,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    unblockPage();

                    table.ajax.reload();
                },
                error: function (error) {
                    unblockPage()

                    showErrorAlert(error.responseJSON.message, () => {
                        KTUtil.scrollTop();
                    });
                }
            });
        });

        $(document).on('click', '#select_all', function () {
            if ($(this).is(":checked")) {
                $('.select-checkbox').attr('checked', true);
            } else {
                $('.select-checkbox').attr('checked', false);
            }
        });

        $(document).on('click', '#doUnAttended', function () {
            var patientIds = $('input:checkbox[name="select-checkbox[]"]:checked').map(function(){return $(this).val()}).get();

            blockPage();

            $.ajax({
                type: 'POST',
                url: FOLLOWUP_STATUS_CHANGE_URL,
                data: {
                    _token: CSRF_TOKEN,
                    patient_ids: patientIds,
                },
                success: function (data) {
                    unblockPage();

                    $('#select_all').attr('checked', false);
                    $('.select-checkbox').attr('checked', false);

                    table.ajax.reload();
                },
                error: function (error) {
                    unblockPage()

                    table.ajax.reload();

                    showErrorAlert(error.responseJSON.message, () => {
                        KTUtil.scrollTop();
                    });
                }
            });
        });

        var branch_id = $('[name="branch_id"]').val();
        // var date = $('[name="date"]').val();
        var start_date = $('[name="start_date"]').val();
        var end_date = $('[name="end_date"]').val();

        function reloadTable() {
            branch_id = $('[name="branch_id"]').val();
            // date = $('[name="date"]').val();
            start_date = $('[name="start_date"]').val();
            end_date = $('[name="end_date"]').val();

            table.ajax.url(
                DATATABLE_URL +
                '?branch_id=' + branch_id +
                // '&date=' + date
                '&start_date=' + start_date +
                '&end_date=' + end_date
            ).load();
        }

        $(document).on('change', '[name="branch_id"]', reloadTable);
        // $(document).on('change', '[name="date"]', reloadTable);
        $(document).on('change', '[name="start_date"]', reloadTable);
        $(document).on('change', '[name="end_date"]', reloadTable);

        reloadTable();
    });
</script>

@endsection
