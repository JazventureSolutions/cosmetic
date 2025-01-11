@extends('layouts.default')

@section('content')

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

            {{-- <div class="form-group mb-0 mr-2">
                <select class="form-control form-control-lg basic-select2" name="year" placeholder="Select Year">
                    <option value="">Select Year</option>
                    @foreach (["2020", "2021", "2022", "2023"] as $year)
                    <option value="{{ $year }}" {{ (old('year', '2021') == $year) ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div> --}}

            <div class="form-group mb-0 mr-2">
                <select class="form-control form-control-lg basic-select2" name="branch_id" placeholder="Select Branch">
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
        <button class="btn btn-light-primary" id="exportExcel">Export Excel</button>
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
            style="margin-top: 13px !important">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>UP TO 06/12</th>
                    <th>06-18/12</th>
                    <th>18-03yrs</th>
                    <th>03-06yrs</th>
                    <th>06-10yrs</th>
                    <th>10-12yrs</th>
                    <th>Over 12yrs </th>
                    <th>Hypospediasis</th>
                    <th>Any Other</th>
                    <th>Peripubic Fat</th>
                    <th>Circumplast</th>
                    <th>Plastibell</th>
                    <th>Resection</th>
                    <th>Bleeding</th>
                    <th>Retained Plastibell</th>
                    <th>Adhesion</th>
                    <th>Division of Adhesion</th>
                    <th>Residual Skin</th>
                    <th>Revisions Follow Ups</th>
                    <th>Followup</th>
                    <th>dna</th>
                    <th>Not Done</th>
                    <th>Consultation Only</th>
                    <th>Not Required</th>
                    <th>Hypopaedias</th>
                    <th>Torsion</th>
                    <th>Fat</th>
                    <th>Hydrocoele</th>
                    <th>Comments</th>
                    <th>Oral</th>
                    <th>Topical</th>
                    <th>Infection</th>
                    <th>Curve</th>
                    <th>Soft Adhesion</th>
                    <th>Chordee</th>
                    <th>Without Father</th>
                    <th>Frenuloplasty</th>
                    <th>Webbed Penis</th>
                    <th>Redundant Inner Skin</th>
                    <th>Secondary Phimosis</th>
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
<script src="{{ url('/') }}/assets/js/pages/widgets.js"></script>
<script src="{{ url('/') }}/assets/js/FileSaver.js"></script>
<script src="{{ url('/') }}/assets/js/xlsx.core.min.js"></script>
<script src="{{ url('/') }}/assets/js/jhxlsx.js"></script>

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
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    // parameters for custom backend script demo
                    columnsDef: [
                        'id',
                        'year',
                        'month',
                        'date',
                        'patient.name',
                        'date_of_birth',
                        'upto_6_12',
                        'for_6_18_12',
                        'for_18_03yrs',
                        'for_03_06yrs',
                        'for_06_10yrs',
                        'for_10_12yrs',
                        'over_12yrs',
                        'hypospediasis',
                        'any_other',
                        'peripubic_fat',
                        'circumplast',
                        'plastibell',
                        'resection',
                        'bleeding',
                        'retained_plastibell',
                        'adhesion',
                        'division_of_adhesion',
                        'residual_skin',
                        'revisions_follow_ups',
                        'followup',
                        'dna',
                        'not_done',
                        'consultation_only',
                        'not_required',
                        'hypopaedias',
                        'torsion',
                        'fat',
                        'hydrocoele',
                        'comments',
                        'oral',
                        'topical',
                        'infection',
                        'curve',
                        'soft_adhesion',
                        'chordee',
                        'without_father',
                        'frenuloplasty',
                        'webbed_penis',
                        'redundant_inner_skin',
                        'secondary_phimosis',
                        'actions',
                    ],
                },
            },
            columns: [
                { data: 'id', searchable: true },
                { data: 'year', searchable: false },
                { data: 'month', searchable: false },
                { data: 'date', searchable: false },
                { data: 'name', name: "patient.name", searchable: true },
                { data: 'date_of_birth', searchable: false },
                { data: 'upto_6_12', searchable: false },
                { data: 'for_6_18_12', searchable: false },
                { data: 'for_18_03yrs', searchable: false },
                { data: 'for_03_06yrs', searchable: false },
                { data: 'for_06_10yrs', searchable: false },
                { data: 'for_10_12yrs', searchable: false },
                { data: 'over_12yrs', searchable: false },
                { data: 'hypospediasis', searchable: false },
                { data: 'any_other', searchable: false },
                { data: 'peripubic_fat', searchable: false },
                { data: 'circumplast', searchable: false },
                { data: 'plastibell', searchable: false },
                { data: 'resection', searchable: false },
                { data: 'bleeding', searchable: false },
                { data: 'retained_plastibell', searchable: false },
                { data: 'adhesion', searchable: false },
                { data: 'division_of_adhesion', searchable: false },
                { data: 'residual_skin', searchable: false },
                { data: 'revisions_follow_ups', searchable: false },
                { data: 'followup', searchable: false },
                { data: 'dna', searchable: false },
                { data: 'not_done', searchable: false },
                { data: 'consultation_only', searchable: false },
                { data: 'not_required', searchable: false },
                { data: 'hypopaedias', searchable: false },
                { data: 'torsion', searchable: false },
                { data: 'fat', searchable: false },
                { data: 'hydrocoele', searchable: false },
                { data: 'comments', searchable: false },
                { data: 'oral', searchable: false },
                { data: 'topical', searchable: false },
                { data: 'infection', searchable: false },
                { data: 'curve', searchable: false },
                { data: 'soft_adhesion', searchable: false },
                { data: 'chordee', searchable: false },
                { data: 'without_father', searchable: false },
                { data: 'frenuloplasty', searchable: false },
                { data: 'webbed_penis', searchable: false },
                { data: 'redundant_inner_skin', searchable: false },
                { data: 'secondary_phimosis', searchable: false },
                { data: 'actions', responsivePriority: -1 },
            ],
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ]
        });

        table.buttons( '.buttons-excel' ).remove();

        $('#exportExcel').on('click', function() {
            $.ajax({
                url : '/patients/get-audit-data',
                type : 'GET',
                data : {
                    'branch_id' : branch_id,
                    'start_date' : start_date,
                    'end_date' : end_date
                },
                dataType:'json',
                success : function(data) {
                   let no_upto_6_12Count = 0;
                    let no_for_6_18_12Count = 0;
                    let no_for_18_03yrsCount = 0;
                    let no_for_03_06yrsCount = 0;
                    let no_for_06_10yrsCount = 0;
                    let no_for_10_12yrsCount = 0;
                    let no_over_12yrsCount = 0;
                    let no_over_hypospediasis = 0;
                    let no_over_any_other = 0;
                    let no_over_peripubic_fat = 0;
                        let no_over_circumplast = 0;
                        let no_over_plastibell = 0;
                        let no_over_resection = 0;
                        let no_over_bleeding = 0;
                        let no_over_retained_plastibell = 0;
                        let no_over_adhesion = 0;
                        let no_over_division_of_adhesion = 0;
                        let no_over_residual_skin = 0;
                        let no_over_revisions_follow_ups = 0;
                        let no_over_followup = 0;
                        let no_over_dna = 0;
                        let no_over_not_done = 0;
                        let no_over_consultation_only = 0;
                        let no_over_not_required = 0;
                        let no_over_hypopaedias = 0;
                        let no_over_torsion = 0;
                        let no_over_fat = 0;
                        let no_over_hydrocoele = 0;
                        let no_over_comments = 0;
                        let no_over_oral = 0;
                        let no_over_topical = 0;
                        let no_over_infection = 0;
                        // let no_over_redundant_inner_skin = 0;
                        // let no_redundant_inner_skin  = 0;
                    var options = {
                        fileName: "audit",
                        extension: ".xlsx",
                        sheetName: "Sheet",
                        fileFullName: "audit.xlsx",
                        header: true,
                    };
                    var tableHead= [
                        'id',
                        'year',
                        'month',
                        'date',
                        'patient.name',
                        'date_of_birth',
                        'upto_6_12',
                        'for_6_18_12',
                        'for_18_03yrs',
                        'for_03_06yrs',
                        'for_06_10yrs',
                        'for_10_12yrs',
                        'over_12yrs',
                        'hypospediasis',
                        'any_other',
                        'peripubic_fat',
                        'circumplast',
                        'plastibell',
                        'resection',
                        'bleeding',
                        'retained_plastibell',
                        'adhesion',
                        'division_of_adhesion',
                        'residual_skin',
                        'revisions_follow_ups',
                        'followup',
                        'dna',
                        'not_done',
                        'consultation_only',
                        'not_required',
                        'hypopaedias',
                        'torsion',
                        'fat',
                        'hydrocoele',
                        'comments',
                        'oral',
                        'topical',
                        'infection',
                        'curve',
                        'soft_adhesion',
                        'chordee',
                        'without_father',
                        'frenuloplasty',
                        'webbed_penis',
                        'redundant_inner_skin',
                        'secondary_phimosis',
                        'actions'
                    ]
                    let apiData = data.data
                    console.log(apiData);
                    if(apiData) {
                        apiData.map((val, k) => {
                             if(val.upto_6_12 === 'Yes') {
                                no_upto_6_12Count++;
                            }
                            if(val.for_6_18_12 === 'Yes') {
                                no_for_6_18_12Count++;
                            }
                            if(val.for_18_03yrs === 'Yes') {
                                no_for_18_03yrsCount++;
                            }
                            if(val.for_03_06yrs === 'Yes') {
                                no_for_03_06yrsCount++;
                            }
                            if(val.for_06_10yrs === 'Yes') {
                                no_for_06_10yrsCount++;
                            }
                            if(val.for_10_12yrs === 'Yes') {
                                no_for_10_12yrsCount++;
                            }
                            if(val.over_12yrs === 'Yes') {
                                no_over_12yrsCount++;
                            }
                            if(val.hypospediasis === 'Yes') {
                                no_over_hypospediasis ++;
                            }
                            if(val.any_other === 'Yes') {
                                no_over_any_other ++;
                            }
                            if(val.peripubic_fat === 'Yes') {
                                no_over_peripubic_fat++;
                            }
                            if(val.circumplast  === 'Yes') {
                                no_over_circumplast  ++;
                            }
                            if(val.plastibell === 'Yes') {
                                no_over_plastibell ++;
                            }
                            if(val.over_12yrs === 'Yes') {
                                no_over_12yrsCount++;
                            }
                            if(val.resection  === 'Yes') {
                                no_over_resection ++;
                            }
                            if(val.bleeding  === 'Yes') {
                                no_over_bleeding ++;
                            }
                            if(val.retained_plastibell  === 'Yes') {
                                no_over_retained_plastibell ++;
                            }
                            if(val.adhesion  === 'Yes') {
                                no_over_adhesion ++;
                            }
                            if(val.division_of_adhesion  === 'Yes') {
                                no_over_division_of_adhesion ++;
                            }
                            if(val.residual_skin  === 'Yes') {
                                no_over_residual_skin ++;
                            }
                            if(val.revisions_follow_ups  === 'Yes') {
                                no_over_revisions_follow_ups ++;
                            }
                            if(val.followup  === 'Yes') {
                                no_over_followup ++;
                            }
                            if(val.dna  === 'Yes') {
                                no_over_dna ++;
                            }
                            if(val.not_done === 'Yes') {
                                no_over_not_done ++;
                            }
                            if(val.consultation_only === 'Yes') {
                                no_over_consultation_only ++;
                            }
                            if(val.not_required === 'Yes') {
                                no_over_not_required ++;
                            }
                            if(val.hypopaedias === 'Yes') {
                                no_over_hypopaedias ++;
                            }
                            if(val.torsion === 'Yes') {
                                no_over_torsion ++;
                            }
                            if(val.fat === 'Yes') {
                                no_over_fat ++;
                            }
                            if(val.over_12yrs === 'Yes') {
                                no_over_12yrsCount++;
                            }
                            if(val.hydrocoele  === 'Yes') {
                                no_over_hydrocoele ++;
                            }
                            if(val.comments  === 'Yes') {
                                no_over_comments ++;
                            }
                            if(val.oral  === 'Yes') {
                                no_over_oral ++;
                            }
                            if(val.topical  === 'Yes') {
                                no_over_topical ++;
                            }
                            if(val.infection  === 'Yes') {
                                no_over_infection ++;
                            }
                            // if(val.redundant_inner_skin  === 'Yes') {
                            //     no_redundant_inner_skin ++;
                            // }
                            // if(val.secondary_phimosis  === 'Yes') {
                            //     no_over_secondary_phimosis ++;
                            // }

                        });
                        let temp= {};
                        temp.id = 'total';
                        temp.patient_id = '';
                        temp.doctor_id = '';
                        temp.notes = '';
                        temp.date = '';
                        temp.start_time = '';
                        temp.end_time = '';
                        temp.status = '';
                        temp.followup_status = '';
                        temp.appointment_type = '';
                        temp.pre_assessment_date = '';
                        temp.followup_date = '';
                        temp.fees = '';
                        temp.fees_paid = '';
                        temp.patient_sign = '';
                        temp.father_sign = '';
                        temp.mother_sign = '';
                        temp.next_kin_sign = '';
                        temp.interpreter_sign = '';
                        temp.sms_sent_at = '';
                        temp.completed_sms_sent_at = '';
                        temp.canceled_sms_sent_at = '';
                        temp.email_sent_at = '';
                        temp.created_at = '';
                        temp.updated_at = '';
                        temp.slot_id = '';
                        temp.branch_id = '';
                        temp.audit = '';
                        temp.year = '';
                        temp.month = '';
                        temp.name = '';
                        temp.date_of_birth = '';
                       temp.upto_6_12 = no_upto_6_12Count;
                        temp.for_6_18_12 = no_for_6_18_12Count;
                        temp.for_18_03yrs = no_for_18_03yrsCount;
                        temp.for_03_06yrs = no_for_03_06yrsCount;
                        temp.for_06_10yrs = no_for_06_10yrsCount;
                        temp.for_10_12yrs = no_for_10_12yrsCount;
                        temp.over_12yrs = no_over_12yrsCount;
                        temp.hypospediasis = no_over_hypospediasis ;
                        temp.any_other = no_over_any_other ;
                        temp.peripubic_fat = no_over_peripubic_fat ;
                        temp.circumplast = no_over_circumplast ;
                        temp.plastibell = no_over_plastibell ;
                        temp.resection = no_over_resection ;
                        temp.bleeding = no_over_bleeding ;
                        temp.retained_plastibell = no_over_retained_plastibell ;
                        temp.adhesion = no_over_adhesion ;
                        temp.division_of_adhesion = no_over_division_of_adhesion;
                        temp.residual_skin = no_over_residual_skin ;
                        temp.revisions_follow_ups = no_over_revisions_follow_ups ;
                        temp.followup = no_over_followup ;
                        temp.dna = no_over_dna ;
                        temp.not_done = no_over_not_done ;
                        temp.consultation_only = no_over_consultation_only ;
                        temp.not_required = no_over_not_required ;
                        temp.hypopaedias = no_over_hypopaedias ;
                        temp.torsion = no_over_torsion ;
                        temp.fat = no_over_fat ;
                        temp.hydrocoele = no_over_hydrocoele ;
                        temp.comments = no_over_comments ;
                        temp.oral = no_over_oral ;
                        temp.topical = no_over_topical ;
                        temp.infection = no_over_infection ;
                        // temp.redundant_inner_skin = no_redundant_inner_skin ;
                        // temp.secondary_phimosis = no_over_secondary_phimosis ;
                        temp.actions = no_upto_6_12Count + no_for_6_18_12Count + no_for_18_03yrsCount + no_for_03_06yrsCount + no_for_06_10yrsCount + no_for_10_12yrsCount + no_over_12yrsCount
                        no_over_hypospediasis + no_over_any_other + no_over_peripubic_fat + no_over_circumplast + no_over_plastibell + no_over_resection + no_over_bleeding + no_over_retained_plastibell +
                        no_over_adhesion + no_over_division_of_adhesion + no_over_residual_skin + no_over_revisions_follow_ups + no_over_followup + no_over_dna + no_over_not_done + no_over_consultation_only
                        + no_over_not_required + no_over_hypopaedias + no_over_torsion + no_over_fat + no_over_hydrocoele + no_over_comments + no_over_oral + no_over_topical + no_over_infection ;
                        apiData.push(temp);
                    }
                    let result = [];
                    result.push(Object.entries(apiData[0]).map(( [k, v] ) => ({ text: (k == null) ? '' : k })))
                    apiData.map((val) => {
                        result.push(Object.entries(val).map(( [k, v] ) => ({ text: (typeof v === 'object') ? '' : v})))
                    })
                    let c_data = {data: result, sheetName: "Sheet1"}
                    let tabData = []
                    tabData.push(c_data)
                    Jhxlsx.export(tabData, options);


                },
                error : function(request,error)
                {
                    alert("Request: "+JSON.stringify(request));
                }
            });
        })

        var branch_id = $('[name="branch_id"]').val();
        // var year = $('[name="year"]').val();
        var start_date = $('[name="start_date"]').val();
        var end_date = $('[name="end_date"]').val();

        function reloadTable() {
            branch_id = $('[name="branch_id"]').val();
            // year = $('[name="year"]').val();
            start_date = $('[name="start_date"]').val();
            end_date = $('[name="end_date"]').val();

            table.ajax.url(
                DATATABLE_URL +
                '?branch_id=' + branch_id +
                // '&year=' + year
                '&start_date=' + start_date +
                '&end_date=' + end_date
            ).load();
        }

        $(document).on('change', '[name="branch_id"]', reloadTable);
        // $(document).on('change', '[name="year"]', reloadTable);
        $(document).on('change', '[name="start_date"]', reloadTable);
        $(document).on('change', '[name="end_date"]', reloadTable);

        reloadTable();
    });
</script>

@endsection
