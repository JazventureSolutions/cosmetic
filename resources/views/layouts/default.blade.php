<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Circumcision Clinic ' . Auth::user()->branch->name }}</title>
    <meta name="description" content="Circumcision Clinic {{ Auth::user()->branch->name }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ url('/') }}/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/plugins/custom/datetimepicker/jquery.datetimepicker.min.css">
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{{ url('/') }}/favicon.ico" />

    <style>
        .aside .aside-primary {
            width: 80px !important;
        }

        .fc-list-item-title a {
            cursor: pointer !important;
        }

        .dataTables_wrapper {
            overflow-x: scroll;
        }

        .dataTables_wrapper .dataTable th, .dataTables_wrapper .dataTable td {
            padding: 1rem 0.5rem;
        }
    </style>

    @yield('styles')

</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body"
    class="header-mobile-fixed subheader-enabled aside-enabled aside-fixed aside-secondary-enabled page-loading">
    <!--begin::Main-->
    <!--begin::Header Mobile-->
    <div id="kt_header_mobile" class="header-mobile">
        <!--begin::Logo-->
        <a href="{{ route('dashboard') }}">
            <img alt="Logo" src="{{ url('/') }}/logo.jpg" class="logo-default max-h-30px" />
        </a>
        <!--end::Logo-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
                <span></span>
            </button>
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">

            @include('partials.aside')

            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Subheader-->
                    <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
                        <div
                            class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                            <!--begin::Details-->
                            <div class="d-flex align-items-center flex-wrap mr-2">
                                <!--begin::Title-->
                                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ $title ?? 'Circumcision Clinic' }}</h5>
                                <!--end::Title-->
                                <!--begin::Separator-->
                                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200">
                                </div>
                                <!--end::Separator-->
                                <!--begin::Search Form-->
                                <div class="d-flex align-items-center" id="kt_subheader_search">
                                    <span class="text-dark-50 font-weight-bold"
                                        id="kt_subheader_total">{{ \Carbon\Carbon::now()->toFormattedDateString() }}</span>
                                </div>
                                <!--end::Search Form-->
                            </div>
                            <!--end::Details-->
                            <!--begin::Toolbar-->
                            <div class="d-flex align-items-center">
                                <!--begin::Button-->
                                <button data-toggle="modal" data-target="#sendReminder"
                                    class="btn btn-light-primary font-weight-bold py-2 px-5 ml-2">Send Reminder SMS</button>
                                <a href="{{ route('patients.appointments.audits') }}"
                                    class="btn btn-light-primary font-weight-bold py-2 px-5 mr-2 ml-2">Audit</a>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="addDropdownMenuButton" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-plus"></i> New
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="addDropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('patients.appointments.add') }}">Appointment</a>
                                        <a class="dropdown-item" href="{{ route('patients.add') }}">Patient</a>
                                        <a class="dropdown-item" href="{{ route('patient-register.add') }}">Inquiry</a>
                                    </div>
                                </div>
                                <a href="{{ route('patients.appointments') }}"
                                    class="btn btn-light-primary font-weight-bold py-2 px-5 ml-2">Appointments</a>
                                <!--end::Button-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                    </div>
                    <!--end::Subheader-->
                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container">
                            @yield('content')
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <!--doc: add "bg-white" class to have footer with solod background color-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted font-weight-bold mr-2">{{ \Carbon\Carbon::now()->year }}</span>
                            <a href="https://www.jazventuresolutions.com/" target="_blank"
                                class="text-dark-75 text-hover-primary">© Jazventure Solutions</a>
                        </div>
                        <!--end::Copyright-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->

    @include('partials.quick-panels')

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
                    <path
                        d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
                        fill="#000000" fill-rule="nonzero" />
                </g>
            </svg>
            <!--end::Svg Icon-->
        </span>
    </div>
    <!--end::Scrolltop-->

    <div class="modal fade" id="sendReminder" tabindex="-1" aria-labelledby="sendReminderLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" style="margin: 0 auto; max-width: 550px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Reminder SMS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="kt_form" class="form" method="POST" action="{{ route('send-reminder') }}">
                        @csrf

                        <!--begin::Group-->
                        <label class="form-label">Reminder Date</label>
                        <div class="form-group">
                            <input
                                class="form-control form-control-lg"
                                type="text" name="reminder_date" placeholder="Reminder Date"
                                value="" required />
                        </div>
                        <!--end::Group-->

                        <!--begin::Group-->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mr-2">Send</button>
                            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                        <!--end::Group-->

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
        var MAIN_URL = "{{ url('/') }}";
    </script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1200
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#1BC5BD",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#6993FF",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#F3F6F9",
                        "dark": "#212121"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#1BC5BD",
                        "secondary": "#ECF0F3",
                        "success": "#C9F7F5",
                        "info": "#E1E9FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#212121",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#ECF0F3",
                    "gray-300": "#E5EAEE",
                    "gray-400": "#D6D6E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#80808F",
                    "gray-700": "#464E5F",
                    "gray-800": "#1B283F",
                    "gray-900": "#212121"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="{{ url('/') }}/assets/plugins/global/plugins.bundle.js"></script>
    <script src="{{ url('/') }}/assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="{{ url('/') }}/assets/js/scripts.bundle.js"></script>
    <!--end::Global Theme Bundle-->

    <script src="{{ url('/') }}/assets/js/pages/my-script.js"></script>
    <script src="{{ url('/') }}/assets/plugins/custom/datetimepicker/jquery.datetimepicker.full.min.js"></script>

    @yield('scripts')

    <script>
        $(document).ready(function () {
            $('#sendReminder [name="reminder_date"]').datetimepicker({
                format: 'Y-m-d',
                timepicker: false,
                inline: true,
                defaultDate: new Date(Date.now() + (3600 * 1000 * 24)),
            });
        });
    </script>

</body>
<!--end::Body-->

</html>
