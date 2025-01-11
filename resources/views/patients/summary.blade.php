@extends('layouts.default-simple')

@php
$hours = [
    ['08', 'AM', '08'],
    ['09', 'AM', '09'],
    ['10', 'AM', '10'],
    ['11', 'AM', '11'],
    ['12', 'PM', '12'],
    ['01', 'PM', '13'],
    ['02', 'PM', '14'],
    ['03', 'PM', '15'],
    ['04', 'PM', '16'],
    ['05', 'PM', '17'],
    ['06', 'PM', '18'],
    ['07', 'PM', '19'],
    ['08', 'PM', '20'],
];
$minutes = ['00','15','30','45'];
@endphp

@section('content')

<div class="card card-custom mb-10">

    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h1>Filter</h1>
        </div>
    </div>

    <div class="card-body">

        <form action="{{ url()->current() }}" method="GET">

            <input type="hidden" name="start_date" value="{{ $form_data['start_date'] ?? null }}">
            <input type="hidden" name="end_date" value="{{ $form_data['end_date'] ?? null }}">

            <div class="form-group">
                <select class="form-control form-control-solid {{ $errors->has('type') ? 'is-invalid' : '' }} basic-select2" name="branch_id" placeholder="Select Branch">
                    <option value="" selected>Select Branch</option>
                    @foreach ($branches as $branchKey => $branch)
                    <option value="{{ $branchKey }}" {{ (($form_data['branch_id'] ?? "") == $branchKey) ? 'selected' : '' }}>{{ $branch }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <input class="form-control form-control-solid" placeholder="Pick date rage" id="date_range"/>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary mr-2">Submit</button>
            </div>

        </form>

    </div>

</div>

@foreach ($appointment_patients as $date => $a_patients)
<div class="card card-custom mb-10">

    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h1>Appointments ({{ \Carbon\Carbon::parse($date)->format('d.m.Y') }})</h1>
        </div>
    </div>

    <div class="card-body">

        @foreach ($hours as $h)
        @foreach ($minutes as $m)
        <div class="col-md-12">
            <div class="row">
                @php
                    $time = $h[2] . ':' . $m;
                    $time_display = $h[0] . ':' . $m . ' ' . $h[1];
                    $data = $a_patients->where('status', '!=', 'canceled')->filter(function ($a_patient) use ($time) { return $a_patient->appointments->filter(function ($a) use ($time) { return $a->start_time == $time; })->count() > 0; });
                @endphp
                <div class="summary-time">
                    {{$time_display}}
                </div>
                @if ($data->count() > 0)
                @foreach ($data as $patient)
                <div class="col-md-12 summary-item">
                    <div class="row">
                        <div class="col-md-3">
                            <b>{{$patient->name}}</b><br>
                            {{$patient->address}}<br><br>
                            <span class="summary-badge" style="background-color: @if($patient->latest_appointment->pre_assessment_date == $date) #5cb054 @else #7d2d2d @endif">{{ $patient->latest_appointment->pre_assessment_date == $date ? "Pre Assesment" : "Appointment"}}</span>
                        </div>
                        <div class="col-md-1">
                            {{\Carbon\Carbon::parse($patient->date_of_birth)->format('d.m.Y')}}
                        </div>
                        <div class="col-md-2">
                            Father: {{$patient->father_name}},<br>
                            Mother: {{$patient->mother_name}}
                        </div>
                        <div class="col-md-1">
                            @if ($patient->latest_appointment)
                            {{$patient->latest_appointment->start_time_formatted}}
                            @endif
                        </div>
                        <div class="col-md-1">
                            @if ($patient->latest_appointment)
                            T: &pound;{{$patient->latest_appointment->fees}}<br>P: &pound;{{$patient->latest_appointment->fees_paid}}<br><b>R: &pound;{{$patient->latest_appointment->fees_remaining}}</b>
                            @endif
                        </div>
                        <div class="col-md-3">
                            Email: {{$patient->email}},<br>{{$patient->cell_number}},<br>{{$patient->phone}}
                        </div>
                        <div class="col-md-1">
                            <a href="{{ $patient->edit_route }}" class="btn btn-block btn-sm btn-primary summary-button mb-2 mr-2" title="Edit details" target="_blank">
                                Patient
                            </a>
                            @if ($patient->latest_appointment)
                            <a href="{{$patient->latest_appointment_route}}" class="btn btn-block btn-sm btn-info summary-button mb-2 mr-2" title="Latest Appointment Reports" target="_blank">
                                Reports
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="col-md-12 summary-item">
                    NO APPOINTMENT FOUND
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @endforeach

        {{-- @foreach ($a_patients->where('status', '!=', 'canceled') as $patient)
        <div class="col-md-12 summary-item">
            <div class="row">
                <div class="col-md-3">
                    <b>{{$patient->name}}</b><br>
                    {{$patient->address}}
                </div>
                <div class="col-md-1">
                    {{\Carbon\Carbon::parse($patient->date_of_birth)->format('d.m.Y')}}
                </div>
                <div class="col-md-2">
                    Father: {{$patient->father_name}},<br>
                    Mother: {{$patient->mother_name}}
                </div>
                <div class="col-md-1">
                    @if ($patient->latest_appointment)
                    {{$patient->latest_appointment->start_time}}
                    @endif
                </div>
                <div class="col-md-1">
                    @if ($patient->latest_appointment)
                    T: &pound;{{$patient->latest_appointment->fees}}<br>P: &pound;{{$patient->latest_appointment->fees_paid}}<br><b>R: &pound;{{$patient->latest_appointment->fees_remaining}}</b>
                    @endif
                </div>
                <div class="col-md-3">
                    Email: {{$patient->email}},<br>{{$patient->cell_number}},<br>{{$patient->phone}}
                </div>
                <div class="col-md-1">
                    <a href="{{ $patient->edit_route }}" class="btn btn-block btn-sm btn-primary mb-2 mr-2" title="Edit details" target="_blank">
                        Patient
                    </a>
                    @if ($patient->latest_appointment)
                    <a href="{{$patient->latest_appointment_route}}" class="btn btn-block btn-sm btn-info mb-2 mr-2" title="Latest Appointment Reports" target="_blank">
                        Reports
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach --}}

    </div>

    {{-- <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h1>Pre Assesments</h1>
        </div>
    </div>

    <div class="card-body">

        @foreach ($pre_assesment_patients->where('pre_assessment_date', $date) as $patient)
        <div class="col-md-12 summary-item">
            <div class="row">
                <div class="col-md-3">
                    <b>{{$patient->name}}</b><br>
                    {{$patient->address}}
                </div>
                <div class="col-md-1">
                    {{\Carbon\Carbon::parse($patient->date_of_birth)->format('d.m.Y')}}
                </div>
                <div class="col-md-2">
                    Father: {{$patient->father_name}},<br>
                    Mother: {{$patient->mother_name}}
                </div>
                <div class="col-md-1">
                    @if ($patient->latest_appointment)
                    {{$patient->latest_appointment->start_time}}
                    @endif
                </div>
                <div class="col-md-1">
                    @if ($patient->latest_appointment)
                    T: &pound;{{$patient->latest_appointment->fees}}<br>P: &pound;{{$patient->latest_appointment->fees_paid}}<br><b>R: &pound;{{$patient->latest_appointment->fees_remaining}}</b>
                    @endif
                </div>
                <div class="col-md-3">
                    Email: {{$patient->email}},<br>{{$patient->cell_number}},<br>{{$patient->phone}}
                </div>
                <div class="col-md-1">
                    <a href="{{ $patient->edit_route }}" class="btn btn-block btn-sm btn-primary mb-2 mr-2" title="Edit details">
                        Patient
                    </a>
                    @if ($patient->latest_appointment)
                    <a href="{{$patient->latest_appointment_route}}" class="btn btn-block btn-sm btn-info mb-2 mr-2" title="Latest Appointment Reports">
                        Reports
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

    </div> --}}

    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h1>Canceled</h1>
        </div>
    </div>

    <div class="card-body">

        @foreach ($a_patients->where('status', 'canceled') as $patient)
        <div class="col-md-12 summary-item">
            <div class="row">
                <div class="col-md-4">
                    <b>{{$patient->name}}</b><br>
                    {{$patient->address}}
                </div>
                <div class="col-md-1">
                    {{\Carbon\Carbon::parse($patient->date_of_birth)->format('d.m.Y')}}
                </div>
                <div class="col-md-2">
                    Father: {{$patient->father_name}},<br>
                    Mother: {{$patient->mother_name}}
                </div>
                <div class="col-md-1">
                    @if ($patient->latest_appointment)
                    {{$patient->latest_appointment->start_time}}
                    @endif
                </div>
                <div class="col-md-1">
                    @if ($patient->latest_appointment)
                    T: &pound;{{$patient->latest_appointment->fees}}<br>P: &pound;{{$patient->latest_appointment->fees_paid}}<br><b>R: &pound;{{$patient->latest_appointment->fees_remaining}}</b>
                    @endif
                </div>
                <div class="col-md-3">
                    Email: {{$patient->email}},<br>{{$patient->cell_number}},<br>{{$patient->phone}}
                </div>
            </div>
        </div>
        @endforeach

    </div>

</div>
@endforeach

@endsection


@section('styles')

{{-- <link href="{{ url('/') }}/assets/plugins/custom/datetimepicker/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css" > --}}
<link href="{{ url('/') }}/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>

<style>
    .summary-item {
        padding: 2px 0px;
        margin: 2px 0px;
        border-bottom: 1px solid #3F425455;
    }

    .summary-time {
        background-color: #2d2f7d;
        color: #fff;
        padding: 2px 10px;
        border-radius: 10px;
    }

    .summary-badge {
        background-color: #2d2f7d;
        color: #fff;
        padding: 2px 10px;
        border-radius: 10px;
    }

    .summary-button {
        min-width: 70px;
    }
</style>

@endsection


@section('scripts')

{{-- <script> --}}
{{-- var AVAILABLE_TIMES_AJAX_URL = '{{ route("patients.appointments.available-times") }}'; --}}
{{-- </script> --}}

<script src="{{ url('/') }}/assets/plugins/global/plugins.bundle.js"></script>
{{-- <script src="{{ url('/') }}/assets/plugins/custom/datetimepicker/jquery.datetimepicker.full.min.js"></script> --}}

<script>
    $(document).ready(function () {
        // $('.basic-select2').select2({
        //     width: "100%",
        //     minimumResultsForSearch: Infinity,
        // });

        $("#date_range").daterangepicker({
            startDate: new Date('{!! $form_data["start_date"] ?? null !!}'),
            endDate: new Date('{!! $form_data["end_date"] ?? null !!}'),
            locale: { format: 'DD.MM.Y' }
        }, function(start, end, label) {
            $('form [name="start_date"]').val(start.format('YYYY-MM-DD'));
            $('form [name="end_date"]').val(end.format('YYYY-MM-DD'));
        });

        // $('[name="date_of_birth"]').datetimepicker({
        //     format: 'd.m.Y',
        //     timepicker: false,
        //     mask: true
        // });

        // function fetchTimes(element) {
        //     blockPage();

        //     var APPOINTMENT_ID = $(element).data('appointment_id');
        //     var PATIENT_ID = $(element).data('patient_id');
        //     var APPOINTMENT_DATE = $(element).data('date');
        //     var SELECTED_START_TIME = $(element).data('start_time');

        //     $.ajax({
        //         type: 'GET',
        //         url: AVAILABLE_TIMES_AJAX_URL + '?patient_id=' + PATIENT_ID + '&appointment_id=' + APPOINTMENT_ID + '&appointment_date=' + APPOINTMENT_DATE,
        //         success: function (data) {
        //             unblockPage();

        //             if (data.status == 'success') {
        //                 $('[name="start_time"][data-appointment_id="' + APPOINTMENT_ID + '"][data-patient_id="' + PATIENT_ID + '"][data-date="' + APPOINTMENT_DATE + '"][data-start_time="' + SELECTED_START_TIME + '"]')
        //                     .empty();
        //                 data.times.forEach(timeObject => {
        //                     $('[name="start_time"][data-appointment_id="' + APPOINTMENT_ID + '"][data-patient_id="' + PATIENT_ID + '"][data-date="' + APPOINTMENT_DATE + '"][data-start_time="' + SELECTED_START_TIME + '"]')
        //                         .append(new Option(timeObject.time_formatted, timeObject.time, timeObject.time == SELECTED_START_TIME, timeObject.time == SELECTED_START_TIME));
        //                         // .append(
        //                         //     '<option value="' + timeObject.time + '" ' +
        //                         //     ((timeObject.available) ? '' : 'disabled') +
        //                         //     ((timeObject.time == SELECTED_START_TIME) ? 'selected' : '') +
        //                         //     '>' + timeObject.time_formatted + '</option>'
        //                         // );
        //                 });
        //             }
        //         },
        //         error: function (error) {
        //             unblockPage();

        //             showErrorAlert(error.responseJSON.message, () => {
        //                 KTUtil.scrollTop();
        //             });
        //         }
        //     });
        // }

        // $('[name="start_time"]').on('select2:opening', function (e) {
        //     fetchTimes(this);
        // })
    });
</script>

@endsection
