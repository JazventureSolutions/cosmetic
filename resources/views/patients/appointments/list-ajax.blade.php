{{-- <div class="row">
    <div class="col-md-12">

        @foreach ($appointments as $appointment)
        <div class="card card-custom mb-10">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-chat-1 text-primary"></i>
                    </span>
                    <h3 class="card-label">Card Toolbar
                        <small>sub title</small></h3>
                </div>
                <div class="card-toolbar">
                    <a href="#" class="btn btn-sm btn-icon btn-light-danger mr-2">
                        <i class="flaticon2-drop"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-icon btn-light-success mr-2">
                        <i class="flaticon2-gear"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-icon btn-light-primary">
                        <i class="flaticon2-bell-2"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">{{ $appointment->notes }}</div>
</div>
@endforeach

</div>
</div> --}}

<div class="timeline timeline-5">
    <div class="timeline-items">
        @foreach ($appointments as $appointment)
        <!--begin::Item-->
        <div class="timeline-item">
            <!--begin::Icon-->
            <div class="timeline-media bg-light-primary">
                <span class="svg-icon svg-icon-primary svg-icon-md">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group-chat.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path
                                d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                                fill="#000000" opacity="0.3" />
                            <path
                                d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                                fill="#000000" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
            </div>
            <!--end::Icon-->
            <!--begin::Info-->
            <div class="timeline-desc timeline-desc-light-primary pb-2">
                {!! $appointment->options_html !!}

                <span class="font-weight-bolder text-primary">
                    {{ $appointment->start_time_formatted }} |
                    {{ $appointment->date_formatted }}
                    <span
                        class="label label-lg font-weight-bold label-inline ml-2timeline-desc timeline-desc-light-primary pb-2"
                        style="background-color: {{ $appointment->status_color['bg_color'] }}; color: {{ $appointment->status_color['text_color'] }}">
                        {{ $appointment->status_text }}
                    </span>
                </span>

                <div class="d-flex flex-column flex-grow-1 mr-2">
                    <span class="text-dark-75 font-size-h6 mb-0">Patient’s Full name:
                        <strong>{{ $appointment->patient->name }}</strong></span>
                </div>

                <p class="font-weight-normal text-dark-50 pb-2">{{ $appointment->notes }}</p>

                {{-- <a href="#" class="btn btn-sm btn-icon btn-light-danger mr-2">
                    <i class="flaticon2-drop"></i>
                </a> --}}
                {{-- <a href="#" class="btn btn-sm btn-icon btn-light-success mr-2">
                    <i class="flaticon2-gear"></i>
                </a> --}}
                {{-- <a href="#" class="btn btn-sm btn-icon btn-light-primary">
                    <i class="flaticon2-bell-2"></i>
                </a> --}}
            </div>
            <!--end::Info-->
        </div>
        <!--end::Item-->
        @endforeach
    </div>
</div>

<style>
    nav {
        margin-top: 22px;
    }
</style>

{{ $appointments->links() }}
