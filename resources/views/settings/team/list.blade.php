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
            <!--begin::Button-->
            <a href="{{ route('settings.team.add') }}" class="btn btn-primary font-weight-bolder">
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
                </span>New Record</a>
            <!--end::Button-->
        </div>
    </div>
    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
            style="margin-top: 13px !important">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Cell Number</th>
                    <th>Verified</th>
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
<script src="{{ url('/') }}/assets/js/pages/team.js"></script>

@endsection
