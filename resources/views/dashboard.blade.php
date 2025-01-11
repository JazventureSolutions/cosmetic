@extends('layouts.default')

@section('content')

<div class="row">
    {{-- <div class="col-xl-4">
        <!--begin::Stats Widget 11-->
        <div class="card card-custom gutter-b">
            <!--begin::Body-->
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                    <span class="symbol symbol-circle symbol-50 symbol-light-danger mr-2">
                        <span class="symbol-label">
                            <span class="svg-icon svg-icon-xl svg-icon-danger">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
                                        <path
                                            d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                    </span>
                    <div class="d-flex flex-column text-right">
                        <span class="text-dark-75 font-weight-bolder font-size-h3">750$</span>
                        <span class="text-muted font-weight-bold mt-2">Weekly Income</span>
                    </div>
                </div>
                <div id="kt_stats_widget_11_chart" class="card-rounded-bottom" data-color="danger"
                    style="height: 150px"></div>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 11-->
    </div> --}}
    {{-- <div class="col-xl-4">
        <!--begin::Stats Widget 10-->
        <div class="card card-custom gutter-b">
            <!--begin::Body-->
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                    <span class="symbol symbol-circle symbol-50 symbol-light-info mr-2">
                        <span class="symbol-label">
                            <span class="svg-icon svg-icon-xl svg-icon-info">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Cart3.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                            fill="#000000" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                    </span>
                    <div class="d-flex flex-column text-right">
                        <span class="text-dark-75 font-weight-bolder font-size-h3">+259</span>
                        <span class="text-muted font-weight-bold mt-2">Sales Change</span>
                    </div>
                </div>
                <div id="kt_stats_widget_10_chart" class="card-rounded-bottom" data-color="info" style="height: 150px">
                </div>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 10-->
    </div> --}}
</div>

<div class="card card-custom gutter-b">
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
    <div class="card-body">

        <div id="kt_calendar"></div>

    </div>
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

<script src="{{ url('/') }}/assets/js/pages/widgets.js"></script>
<script>
    var APPOINTMENTS_AJAX_URL = '{{ route("patients.appointments") }}';
</script>
<script src="{{ url('/') }}/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
<script src="{{ url('/') }}/assets/js/pages/appointments.js"></script>

@endsection
