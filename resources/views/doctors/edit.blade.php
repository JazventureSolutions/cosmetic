@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <!--begin::Card body-->
    <div class="card-body">

        <form id="kt_form" class="form" method="POST" action="{{ route('settings.doctors.store') }}">
            @csrf

            <input type="hidden" name="id" value="{{ $doctor->id ?? 0 }}">

            <!--begin::Row-->
            <div class="row">
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Doctor Name</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            type="text" name="name" placeholder="Doctor Name"
                            value="{{ old('name', $doctor->name ?? '') }}" required />

                        @if ($errors->has('name'))
                        <span class=" invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Town/City</label>
                        <div class="input-group input-group-lg {{ $errors->has('city') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-home"></i>
                                </span>
                            </div>
                            <input type="text"
                                class="form-control form-control-lg {{ $errors->has('city') ? 'is-invalid' : '' }}"
                                name="city" value="{{ old('city', $doctor->city ?? '') }}"
                                placeholder="Town/City" />
                        </div>

                        @if ($errors->has('city'))
                        <span class=" invalid-feedback">{{ $errors->first('city') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Mobile</label>
                        <div
                            class="input-group input-group-lg {{ $errors->has('cell_number') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-phone"></i>
                                </span>
                            </div>
                            <input type="tel"
                                class="form-control form-control-lg {{ $errors->has('cell_number') ? 'is-invalid' : '' }}"
                                name="cell_number" value="{{ old('cell_number', $doctor->cell_number ?? '') }}"
                                placeholder="Mobile" />
                        </div>

                        @if ($errors->has('cell_number'))
                        <span class=" invalid-feedback">{{ $errors->first('cell_number') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">House Telephone</label>
                        <div class="input-group input-group-lg {{ $errors->has('phone') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-phone"></i>
                                </span>
                            </div>
                            <input type="tel"
                                class="form-control form-control-lg {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                name="phone" value="{{ old('phone', $doctor->phone ?? '') }}"
                                placeholder="House Telephone" />
                        </div>

                        @if ($errors->has('phone'))
                        <span class=" invalid-feedback">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-group input-group-lg {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-at"></i>
                                </span>
                            </div>
                            <input type="email"
                                class="form-control form-control-lg {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                name="email" value="{{ old('email', $doctor->email ?? '') }}"
                                placeholder="Email Address" />
                        </div>

                        @if ($errors->has('email'))
                        <span class=" invalid-feedback">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-3">
                    <label>Sign</label>
                    <img src="{{ old('sign', $doctor->sign) }}" class="canvas-sign" id="canvas-doctor-sign" />
                    <input type="hidden" name="sign" value="{{ old('sign', $doctor->sign) }}">
                    <button type="button" class="btn btn-primary btn-block btn-sign"
                        data-sign-type="doctor">Manage Signature</button>
                </div>
                <div class="col-md-12 my-2">

                    <!--begin::Group-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
                    </div>

                </div>
            </div>
            <!--end::Row-->

        </form>

    </div>
    <!--begin::Card body-->
</div>

<div class="modal fade" id="signModal" tabindex="-1" aria-labelledby="signModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
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

@endsection


@section('styles')

<link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/plugins/custom/datetimepicker/jquery.datetimepicker.min.css">
<style>
    .canvas-main {
        width: 300px;
        height: 150px;
        border: 1px solid;
    }
    .canvas-sign {
        width: 100%;
        min-width: 150px;
        min-height: 150px;
        border: 1px solid;
    }
</style>

@endsection


@section('scripts')
<script>
    $(document).ready(function () {
        $('#signModal').on('shown.bs.modal', function (e) {
            $('html').css('overflow', 'hidden');
        });

        $('#signModal').on('hidden.bs.modal', function (e) {
            $('html').css('overflow', 'unset');
        });

        $(document).on('click', '.btn-sign', function () {
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
            var sigImage = document.getElementById("canvas-" + _TYPE + "-sign");
            var clearBtn = document.getElementById("sig-clearBtn");
            var submitBtn = document.getElementById("sig-submitBtn");

            clearBtn.addEventListener("click", clearCanvas, false);

            function submitCanvas(e) {

                var dataUrl = canvas.toDataURL();
                sigImage.setAttribute("src", dataUrl);

                $('[name="sign"]').val(dataUrl);

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
    });
</script>

@endsection
