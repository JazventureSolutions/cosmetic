@extends('layouts.default-simple')

@section('content')

<div class="card">
    <!--begin::Card body-->
    <div class="card-body">

        <a href="{{ route('dashboard') }}">
            <img alt="Logo" src="{{ url('/') }}/logo.jpg" style="display: block;margin: auto;" />
        </a>

        <div class="card mt-6">
            <div class="card-body">
                Please click on below box to register your signature
            </div>
        </div>

        <form id="patient_form" class="form" method="POST" action="{{ route('remote-parent-signature.postsignature') }}">
            @csrf
            <input type="hidden" name="id" value="{{ $remote_parent_signature->id }}">
            <input type="hidden" name="parent_sign" value="">
            <!--begin::Row-->
            <div class="row">
            <div class="col-md-5 my-2">
            </div>
            <div class="col-md-4 my-2">
            <div style="margin-bottom: 18px;">
                <div class="html-content">
                        <img data-sign-type="parent" class="sign-class"/>
                    </div>
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
                <div class="col-md-12 my-2">
                    <!--begin::Group-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3 my-2">
            </div>
            </div>
            <!--end::Row-->
        </form>

    </div>
    <!--begin::Card body-->
</div>

@endsection


@section('styles')
<style>
    .html-content img.sign-class {
        height: 60px;
        min-width: 100px;
        max-width: 100px;
        border: 1px dotted #888;
    }
    .html-modal > img, .html-content > img {
        /* position: absolute;
        z-index: 2; */
        width: 720px;
    }
    .canvas-main {
        width: 300px;
        height: 150px;
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

    $(document).on('click', '.sign-class', function () {

        $('#signModal').modal('show');

        var SIGN_TYPE = $(this).data('sign-type');
        console.log(SIGN_TYPE);
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

            $('[name="' + _TYPE + '_sign"]').val(dataUrl);
            console.log(_TYPE);
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
    });
</script>

@endsection
