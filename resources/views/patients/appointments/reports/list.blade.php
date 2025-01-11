@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <!--begin::Card body-->
    <div class="card-body">

        <div class="row">
            <div class="col-12" style="margin-bottom: 14px;">
                <h4>Appointment Date: <b>{{ $appointment->date_formatted }} @ {{ $appointment->start_time_formatted }}</b></h4>
                <a href="{{ route('patients.appointments.feedback', ['appointment_id' => $appointment->id]) }}" class="btn btn-success ml-2"
                    style="float: right"
                    title="Feedback">
                    <i class="far fa-file-pdf icon-md"></i> Feedback
                </a>
                <a href="{{ route('patients.appointments.audit', ['appointment_id' => $appointment->id]) }}" class="btn btn-success ml-2"
                    style="float: right"
                    title="Audit Report">
                    <i class="far fa-file-pdf icon-md"></i> Audit Report
                </a>
            </div>
            <div class="col-3">
                <h5>Used Reports</h5>
                <ul class="nav flex-column nav-pills mb-5">
                    @foreach ($reports as $key => $report)
                    @php
                        $_report = $patient_reports->where('template_id', $report->id);
                    @endphp
                    @if (count($_report) > 0)
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ $key == 0 ? 'active' : '' }}" data-toggle="tab"
                            href="#report-{{ $report->id }}">
                            <span class="nav-icon">
                                <i class="far fa-file-pdf icon-md"></i>
                            </span>
                            <span class="nav-text">{{ $report->name }}</span>
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
                <h5>Additional Reports</h5>
                <ul class="nav flex-column nav-pills mb-5">
                    @foreach ($patient->additional_reports as $key => $additional_report)
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="{{ $additional_report->file_url }}" target="_blank">
                            <span class="nav-icon">
                                <i class="fa fa-external-link-alt icon-md"></i>
                            </span>
                            <span class="nav-text">{{ $additional_report->name }}</span>
                            <span class="nav-icon">
                                <form method="POST"
                                    action="{{ route('patients.appointments.reports.delete', ['appointment_id' => $appointment->id ?? 0]) }}"
                                    onsubmit="return confirm('Are you sure ?');">
                                    @csrf
                                    <input type="hidden" name="report_id" value="{{ $additional_report->id }}">
                                    <button class="btn" type="submit"><i class="fa fa-trash icon-md"></i></button>
                                </form>
                            </span>
                        </a>
                    </li>
                    @endforeach
                    <button class="btn btn-link btn-add-report">Add Report</button>
                </ul>
                <div class="accordion accordion-toggle-arrow" id="accordionExample1">
                    @foreach ($patient_types as $patient_type_key => $patient_type)
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title" data-toggle="collapse" data-target="#UnsedReports{{ $patient_type_key }}">
                                Unsed Reports ({{ $patient_type }})
                            </div>
                        </div>
                        <div id="UnsedReports{{ $patient_type_key }}" class="collapse" data-parent="#accordionExample1">
                            <div class="card-body">
                                <ul class="nav flex-column nav-pills">
                                    @foreach ($reports->where('patient_type', $patient_type_key) as $key => $report)
                                    @php
                                        $_report = $patient_reports->where('template_id', $report->id);
                                    @endphp
                                    @if (count($_report) == 0)
                                    <li class="nav-item mb-2">
                                        <a class="nav-link {{ $key == 0 ? 'active' : '' }}" data-toggle="tab"
                                            href="#report-{{ $report->id }}">
                                            <span class="nav-icon">
                                                <i class="far fa-file-pdf icon-md"></i>
                                            </span>
                                            <span class="nav-text">{{ $report->name }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div id="reports-parent" class="col-9">
                <div class="tab-content">
                    @foreach ($reports as $key => $report)
                    <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}" id="report-{{ $report->id }}"
                        role="tabpanel">

                        @php
                            $_report = $patient_reports->where('template_id', $report->id);
                        @endphp

                        <div class="html-options">
                            <button type="button" class="btn btn-primary ml-2 btn-print-html"
                                onclick="printContent('html-content-{{ $report->id }}')" style="float: right"
                                title="Print">
                                <i class="far fa-file-pdf icon-md"></i> Print
                            </button>
                            @if (count($_report) > 0)
                            <button type="button" class="btn btn-danger ml-2 btn-unsave-report"
                                title="Unsave Report"
                                data-report-id="{{ $report->id }}">
                                <i class="far fa-file-pdf icon-md"></i> Unsave
                            </button>
                            @endif
                            <button type="button" class="btn btn-primary ml-2 btn-save-report"
                                onclick="saveContent('html-content-{{ $report->id }}', 'sign')" style="float: right"
                                title="Save Report"
                                data-report-id="{{ $report->id }}">
                                <i class="far fa-file-pdf icon-md"></i> Save
                            </button>
                            <button type="button" class="btn btn-primary ml-2 btn-edit-html"
                                onclick="editContent('html-content-{{ $report->id }}')" style="float: right"
                                title="Edit">
                                <i class="far fa-file-pdf icon-md"></i> Edit
                            </button>
                            <button type="button" class="btn btn-primary ml-2 btn-cancel-save-content"
                                onclick="cancelSaveContent('html-content-{{ $report->id }}')" style="float: right; display: none"
                                title="Cancel">
                                <i class="far fa-file-pdf icon-md"></i> Cancel
                            </button>
                            <button type="button" class="btn btn-primary ml-2 btn-save-content"
                                onclick="saveContent('html-content-{{ $report->id }}', 'tinymce')" style="float: right; display: none"
                                title="Save">
                                <i class="far fa-file-pdf icon-md"></i> Save
                            </button>
                        </div>

                        <div class="html-parent">

                            {{-- <input type="hidden" name="sign_html_{{ $report->id }}" value="{{ count($_report) > 0 ? $_report->last()->sign_html : '' }}"> --}}

                            <img id="html-content-{{ $report->id }}-sign" src="{{ count($_report) > 0 ? $_report->last()->sign_html : '' }}" style="position: absolute;z-index: 2;">

                            <div id="html-content-{{ $report->id }}-backup" style="display: none">
                                @if (count($_report) > 0)
                                {!! \App\Helpers\Helper::fillReport($appointment->id, $_report->last()->html) !!}
                                @else
                                {!! \App\Helpers\Helper::fillReport($appointment->id, $report->html) !!}
                                @endif
                            </div>

                            <div id="html-content-{{ $report->id }}" class="html-content" style="width: 100%;float: left;">
                                @if (count($_report) > 0)
                                {!! \App\Helpers\Helper::fillReport($appointment->id, $_report->last()->html) !!}
                                @else
                                {!! \App\Helpers\Helper::fillReport($appointment->id, $report->html) !!}
                                @endif
                            </div>

                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
    <!--begin::Card body-->
</div>

<div class="modal fade" id="signModal" tabindex="-1" aria-labelledby="signModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content" style="margin: 0 auto; max-width: 550px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sign</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="float: right">
                    <button type="button" class="btn btn-danger" id="sig-clearBtn">Clear</button>
                    <button type="button" class="btn btn-success" id="sig-submitBtn">Save</button>
                </div>
                <canvas id="canvas-main" class="canvas-main"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addAdditionalReportModal" tabindex="-1" aria-labelledby="addAdditionalReportModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content" style="margin: 0 auto; max-width: 550px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Additional Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="kt_form" class="form" method="POST" action="{{ route('patients.appointments.reports.add', ['appointment_id' => $appointment->id ?? 0]) }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $appointment->id ?? 0 }}">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Report name</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            type="text" name="name" placeholder="Report name"
                            value="{{ old('name', '') }}" required />

                        @if ($errors->has('name'))
                        <span class=" invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Report</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('file') ? 'is-invalid' : '' }}"
                            type="file" name="file" placeholder="Report"
                            required />

                        @if ($errors->has('file'))
                        <span class=" invalid-feedback">{{ $errors->first('file') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                    <!--begin::Group-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                    <!--end::Group-->

                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section('styles')
<style>
    .canvas-main {
        width: 300px;
        height: 150px;
        border: 1px solid;
    }

    .html-options {
        float: right;
        display: block;
        width: 720px;
    }

    .html-parent {
        position: relative;
        float: right;
        width: 720px;
        display: flex;
        justify-content: center;
    }

    .html-content img.sign-class {
        height: 60px;
        min-width: 100px;
        border: 1px dotted #888;
    }

    .html-modal {
        position: relative;
        /* float: right; */
        width: 720px;
        /* display: flex; */
        /* justify-content: center; */
        display: block;
        margin: 0 auto;
    }

    .html-modal > section {
        width: 720px;
    }

    .html-modal > canvas {
        position: absolute;
        z-index: 2;
    }

    .html-modal > img, .html-content > img {
        position: absolute;
        z-index: 2;
        width: 720px;
    }

    .modal-dialog.modal-xl {
        max-width: 95vw;
    }

    .nav.nav-pills .nav-item {
        background-color: #f1f4f7;
        border-radius: 4px;
    }

    .tab-content, .tab-pane {
        float: left;
    }

    #reports-parent .tab-pane {
        -webkit-transform-origin: top left;
    }

    .c-checkbox {
        display: inline-block;
        height: 15px;
        width: 15px;
        border: 1px solid #11125c;
        background-color: transparent;
        margin: 0 10px;
    }

    .c-checkbox.checked {
        background-color: #11125c;
    }

</style>
@endsection


@section('scripts')
<script src="https://cdn.tiny.cloud/1/2me38emy24f0gwihie60kmho6as74v8zdoq7vkabuw1egag1/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>

    $(document).on('click', '.btn-add-report', function () {
        $('#addAdditionalReportModal').modal('show');
    });

    $(document).on('click', '.c-checkbox', function () {
        if ($(this).hasClass('checked')) {
            $(this).removeClass('checked');
        } else {
            $(this).addClass('checked');
        }
    });

    $(document).on('click', '.btn-unsave-report', function () {
        if (confirm('Are you sure?')) {
            blockPage();

            var params = {
                patient_id: "{{ $patient->id }}",
                template_id: $(this).data('report-id'),
                _token: "{{ csrf_token() }}",
            };

            $.ajax({
                type: 'POST',
                url: "{{ route('patients.appointments.reports.unsave', ['appointment_id' => $appointment->id]) }}",
                data: params,
                success: function (data) {
                    unblockPage();
                },
                error: function (error) {
                    unblockPage()

                    showErrorAlert(error.responseJSON.message, () => {
                        KTUtil.scrollTop();
                    });
                }
            });
        }
    });

    function resizeReport() {
        setTimeout(() => {

            var maxWidth  = $('#reports-parent').width();
            // // var maxHeight = $('#reports-parent').height();

            var width = 720;
            // // var height = $window.height();
            var scale = maxWidth/width;

            console.log('parent => ' + maxWidth);
            console.log('to scale => ' + width);
            console.log('scale => ' + scale);

            // // $('#reports-parent .tab-pane').css({ width: maxWidth });
            $('#reports-parent .tab-pane').css({'-webkit-transform': 'scale(' + (scale > 1 ? 1 : scale) + ')'});

        }, 1000);
    }

    $(window).resize(resizeReport);
    resizeReport();

    function post(path, params, method='post') {

        // The rest of this code assumes you are not using a library.
        // It can be made less verbose if you use one.
        const form = document.createElement('form');
        form.method = method;
        form.action = path;

        for (const key in params) {
            if (params.hasOwnProperty(key)) {
                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = params[key];

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }

    function editContent(el) {

        $('#' + el).closest('.html-parent').find('#' + el + '-sign').hide();

        tinymce.init({
            selector: '#' + el,
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | template link anchor codesample | ltr rtl',
            toolbar_mode: 'sliding',
            contextmenu: 'link table',
        });

        $('.btn-print-html').hide();
        $('.btn-save-report').hide();
        $('.btn-edit-html').hide();
        $('.btn-save-content').show();
        $('.btn-cancel-save-content').show();
    }

    function cancelSaveContent(el) {

        tinymce.get(el).remove();

        $('.btn-print-html').show();
        $('.btn-save-report').show();
        $('.btn-edit-html').show();
        $('.btn-save-content').hide();
        $('.btn-cancel-save-content').hide();

        $('#' + el).html($('#' + el + '-backup').html());
        $('#' + el).closest('.html-parent').find('#' + el + '-sign').show();
    }

    function saveContent(el, type = 'tinymce') {

        // tinymce/sign

        // var html_content = (type == 'tinymce')
        //     ? encodeURIComponent(tinymce.get(el).getContent())
        //     : ((type == 'sign') ? $('#' + el).html() : '');

        var html_content = (type == 'tinymce')
            ? (tinymce.get(el).getContent())
            : ((type == 'sign') ? $('#' + el).html() : '');

        if (type == 'tinymce') {
            var $html = $('<div />',{html:html_content});
            $html.find('[data-sign-type="followup"]').removeAttr('src');
            html_content = $html.html();
        }

        var params = {
            patient_id: "{{ $patient->id }}",
            template_id: el.split('-')[2],
            html_content: html_content,
            sign_html_content: $('#' + el + '-sign').attr('src'),
            _token: "{{ csrf_token() }}",
        };

        blockPage();

        $.ajax({
            type: 'POST',
            url: "{{ route('patients.appointments.reports.save', ['appointment_id' => $appointment->id]) }}",
            data: params,
            success: function (data) {
                unblockPage();
                $('#' + el + '-backup').html(html_content);
                $('#' + el).html(html_content);
            },
            error: function (error) {
                unblockPage()

                showErrorAlert(error.responseJSON.message, () => {
                    KTUtil.scrollTop();
                });
            }
        });

        if (type == 'tinymce') {
            tinymce.get(el).remove();
        }
        $('#' + el).closest('.html-parent').find('#' + el + '-sign').show();

        $('.btn-print-html').show();
        $('.btn-save-report').show();
        $('.btn-edit-html').show();
        $('.btn-save-content').hide();
        $('.btn-cancel-save-content').hide();
    }

    function printContent(el) {

        var params = {
            patient_id: "{{ $patient->id }}",
            template_id: el.split('-')[2],
            html_content: encodeURIComponent($('#' + el).html()),
            sign_html_content: $('#' + el + '-sign').attr('src'),
            _token: "{{ csrf_token() }}",
        };

        blockPage();

        $.ajax({
            type: 'POST',
            url: "{{ route('patients.appointments.reports.print', ['appointment_id' => $appointment->id]) }}",
            data: params,
            success: function (data) {
                unblockPage();

                window.open(data.print_url, '_blank');

                // var win = window.open("", "Print Report", "");
                // win.document.write(data.print_html);

            },
            error: function (error) {
                unblockPage();

                showErrorAlert(error.responseJSON.message, () => {
                    KTUtil.scrollTop();
                });
            }
        });

    }

    var canvas = null;
    var canvasHeight = 0;
    var canvasWidth = 0;

    $('#signModal').on('shown.bs.modal', function (e) {
        $('html').css('overflow', 'hidden');
    });

    $('#signModal').on('hidden.bs.modal', function (e) {
        $('html').css('overflow', 'unset');
    });

    $(document).on('click', '.sign-class', function () {
        $('#signModal').modal('show');

        var SIGN_TYPE = $(this).data('sign-type');

        handleSign(SIGN_TYPE);
    });

    function handleSign(_TYPE) {
        window.requestAnimFrame = (function(callback) {
            return window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimaitonFrame ||
            function(callback) {
                window.setTimeout(callback, 1000 / 60);
            };
        })();

        canvasHeight = 150; //document.getElementById("html-content-" + _TYPE).querySelector('section').offsetHeight;
        canvasWidth = 300; //document.getElementById("html-content-" + _TYPE).querySelector('section').offsetWidth;

        canvas = document.getElementById("canvas-main");
        canvas.height = canvasHeight;
        canvas.width = canvasWidth;

        var ctx = canvas.getContext("2d");
        ctx.strokeStyle = "#000";
        ctx.lineWidth = 1;

        var drawing = false;
        var mousePos = {
            x: 0,
            y: 0
        };
        var lastPos = mousePos;

        function canvasmousedown(e) {
            drawing = true;
            lastPos = getMousePos(canvas, e);
        }

        canvas.addEventListener("mousedown", canvasmousedown, false);

        function canvasmouseup(e) {
            drawing = false;
        }

        canvas.addEventListener("mouseup", canvasmouseup, false);

        function canvasmousemove(e) {
            mousePos = getMousePos(canvas, e);
        }

        canvas.addEventListener("mousemove", canvasmousemove, false);

        function canvastouchmove(e) {
            var touch = e.touches[0];
            var me = new MouseEvent("mousemove", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(me);
        }

        canvas.addEventListener("touchmove", canvastouchmove, false);

        function canvastouchstart(e) {
            mousePos = getTouchPos(canvas, e);
            var touch = e.touches[0];
            var me = new MouseEvent("mousedown", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(me);
        }

        canvas.addEventListener("touchstart", canvastouchstart, false);

        function canvastouchend(e) {
            var me = new MouseEvent("mouseup", {});
            canvas.dispatchEvent(me);
        }

        canvas.addEventListener("touchend", canvastouchend, false);

        function getMousePos(canvasDom, mouseEvent) {
            var rect = canvasDom.getBoundingClientRect();
            return {
                x: mouseEvent.clientX - rect.left,
                y: mouseEvent.clientY - rect.top
            }
        }

        function getTouchPos(canvasDom, touchEvent) {
            var rect = canvasDom.getBoundingClientRect();
            return {
                x: touchEvent.touches[0].clientX - rect.left,
                y: touchEvent.touches[0].clientY - rect.top
            }
        }

        function renderCanvas() {
            if (drawing) {
                ctx.moveTo(lastPos.x, lastPos.y);
                ctx.lineTo(mousePos.x, mousePos.y);
                ctx.stroke();
                lastPos = mousePos;
            }
        }

        // Prevent scrolling when touching the canvas
        function documenttouchstart(e) {
            if (e.target == canvas) {
                e.preventDefault();
            }
        }
        document.body.addEventListener("touchstart", documenttouchstart, false);
        function documenttouchend(e) {
            if (e.target == canvas) {
                e.preventDefault();
            }
        }
        document.body.addEventListener("touchend", documenttouchend, false);
        function documenttouchmove(e) {
            if (e.target == canvas) {
                e.preventDefault();
            }
        }
        document.body.addEventListener("touchmove", documenttouchmove, false);

        (function drawLoop() {
            requestAnimFrame(drawLoop);
            renderCanvas();
        })();

        function clearCanvas() {
            canvas.width = canvas.width;
        }

        // Set up the UI
        var sigImage = $('[data-sign-type="' + _TYPE + '"]'); // document.getElementById("canvas-" + _TYPE + "-sign");
        var clearBtn = document.getElementById("sig-clearBtn");
        var submitBtn = document.getElementById("sig-submitBtn");

        clearBtn.addEventListener("click", clearCanvas, false);

        function submitCanvas(e) {
            var dataUrl = canvas.toDataURL();
            sigImage.attr("src", dataUrl);

            // $('[name="' + _TYPE + '_sign"]').val(dataUrl);

            canvas.removeEventListener("mousedown", canvasmousedown);
            canvas.removeEventListener("mouseup", canvasmouseup);
            canvas.removeEventListener("mousemove", canvasmousemove);
            canvas.removeEventListener("touchmove", canvastouchmove);
            canvas.removeEventListener("touchstart", canvastouchstart);
            canvas.removeEventListener("touchend", canvastouchend);
            canvas.removeEventListener("touchstart", canvastouchstart);

            document.body.removeEventListener("touchstart", documenttouchstart);
            document.body.removeEventListener("touchend", documenttouchend);
            document.body.removeEventListener("touchmove", documenttouchmove);

            clearBtn.removeEventListener("click", clearCanvas);
            submitBtn.removeEventListener("click", submitCanvas);

            $('#signModal').modal('hide');
        }

        submitBtn.addEventListener("click", submitCanvas, false);
    }
</script>
@endsection
