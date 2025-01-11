@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            {{-- <h3 class="card-label">Remote Datasource --}}
            {{-- <span class="d-block text-muted pt-2 font-size-sm">Sorting &amp; pagination remote datasource</span> --}}
            {{-- </h3> --}}
        </div>
        <div class="card-toolbar">

            <div class="form-group mb-0">
                <select class="form-control form-control-lg {{ $errors->has('type') ? 'is-invalid' : '' }} basic-select2" name="branch_id" placeholder="Select Branch">
                    <option value="">Select Branch</option>
                    @foreach ($branches as $branchKey => $branch)
                    <option value="{{ $branchKey }}" {{ (old('branch_id', Auth::user()->branch_id) == $branchKey) ? 'selected' : '' }}>{{ $branch }}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
    <!--begin::Card body-->
    <div class="card-body">

        <div id="kt_calendar"></div>

    </div>
    <!--begin::Card body-->
</div>

@endsection


@section('styles')
<link href="{{ url('/') }}/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet"
    type="text/css" />

<style>
    .fc-list-item-options {
        float: right !important;
    }
</style>
@endsection


@section('scripts')
<script>
    var APPOINTMENTS_AJAX_URL = '{{ route("patients.appointments") }}';
</script>
<script src="{{ url('/') }}/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
<!-- <script src="{{ url('/') }}/assets/js/pages/appointments.js"></script> -->
@endsection
