@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <!--begin::Card body-->
    <div class="card-body">
        <form id="patient_form" class="form" method="POST" action="{{ route('remote-parent-signature.store') }}">
            @csrf
            <input type="hidden" name="id" value="{{ $remote_parent_signature->id }}">
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-6">
                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Patient</label>
            
                        <a href="{{ route('patients.add') }}" class="btn btn-link p-0" target="_blank" style="float: right">Add Patient</a>
    
                        <select class="form-control form-control-lg {{ $errors->has('patient') ? 'is-invalid' : '' }} patient-select2" name="patient_id" placeholder="Patient">
                            @if ($remote_parent_signature->patient_id)
                            <option  value="{{ $patient->id }}" {{ (old($patient->id, $remote_parent_signature->patient_id ?? '') == $patient->id) ? 'selected' : '' }}>{{ $patient->name }}</option>
                            @else
                            <option value="">Select</option>
                            @endif
                        </select>

                        @if ($errors->has('patient'))
                        <span class=" invalid-feedback">{{ $errors->first('patient') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->
                </div>

                <div class="col-md-6">
                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Patient Type</label>
                        <select class="form-control form-control-lg {{ $errors->has('type') ? 'is-invalid' : '' }} basic-select2"
                            name="type" placeholder="Patient Type">
                            @foreach ($patient_types as $typeKey => $type)
                            <option value="{{ $typeKey }}" {{ (old('type', $remote_parent_signature->type ?? '') == $typeKey) ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                            @endforeach
                        </select>

                        @if ($errors->has('type'))
                        <span class=" invalid-feedback">{{ $errors->first('type') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>

                <div class="col-md-6">
                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Patient Parent Type</label>
                        <select class="form-control form-control-lg {{ $errors->has('patient_parent_type') ? 'is-invalid' : '' }} basic-select2"
                            name="parentType" placeholder="Patient Type">
                            @foreach ($patient_parent_types as $parentTypeKey => $parentType)
                            <option value="{{ $parentTypeKey }}" {{ (old('parentType', $remote_parent_signature->patient_parent_type ?? '') == $parentTypeKey) ? 'selected' : '' }}>
                                {{ $parentType }}
                            </option>
                            @endforeach
                        </select>

                        @if ($errors->has('parentType'))
                        <span class=" invalid-feedback">{{ $errors->first('parentType') }}</span>
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
                                name="cell_number" value="{{ old('cell_number', $remote_parent_signature->cell_number ?? '') }}"
                                placeholder="Mobile" />
                        </div>

                        @if ($errors->has('cell_number'))
                        <span class=" invalid-feedback">{{ $errors->first('cell_number') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->
                </div>
                @if($remote_parent_signature->id == null)
                <div class="col-md-12 my-2">
                    <!--begin::Group-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </div>
                </div>
                @else
                    @if($remote_parent_signature->is_submit == 0)
                    <div class="col-md-12 my-2">
                        <!--begin::Group-->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mr-2">Re Submit</button>
                        </div>
                    </div>
                    @endif
                @endif
                
            </div>
            <!--end::Row-->

            @if($remote_parent_signature->id != null)
            <!--begin::Group-->
            <div class="form-group">
                <label class="form-label">Register Link</label>
                <input disabled
                    class="form-control form-control-lg {{ $errors->has('link') ? 'is-invalid' : '' }}"
                    type="text" name="link" placeholder="#"
                    value="{{ old('link', $remote_parent_signature->link ?? '') }}" required />

                @if ($errors->has('link'))
                <span class=" invalid-feedback">{{ $errors->first('link') }}</span>
                @endif
            </div>
            <!--end::Group-->
            @endif
            @if($remote_parent_signature->id != null && $remote_parent_signature->is_submit == 1 && $remote_parent_signature->is_approve == 0)
            <div class="form-group">
                <div class="html-content">
                    <img data-sign-type="doctor" class="sign-class" src="{{$remote_parent_signature->signature}}"/>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="approve_reject" value="">
                <button type="submit" id="approveButton" class="btn btn-primary mr-2">Approve</button>
                <button type="submit" id="rejectButton" class="btn btn-primary mr-2">Reject</button>
            </div>
            @endif

            

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
        $("#approveButton").click(function() {
            var confirmation = confirm("Are you sure you want to submit this form?");
            if(confirmation == true){
                $('[name="approve_reject"]').val(1);
            }
        });

        $("#rejectButton").click(function() {
            var confirmation = confirm("Are you sure you want to submit this form?");
            if(confirmation == true){
                $('[name="approve_reject"]').val(2);
            }
        });

        $('.patient-select2').select2({
            ajax: {
                url: '{{ route("patients.select2") }}',
                dataType: 'json'
            }
        });

        $('.patient-select2').on('select2:select', function (e) {
            var data = e.params.data;
            switch (data.type) {
                case 'adult':
                    $('.hide-adult').hide();
                    $('.hide-old_boy').show();
                    $('.hide-new_born').show();
                    break;

                default:
                    $('.hide-adult').show();
                    $('.hide-old_boy').hide();
                    $('.hide-new_born').hide();
                    break;
            }
            $('[name="patient_id"]').val(data.id);
        });

        $('.patient-select2').trigger('select.select2');
        // -------------------------------------------------------------
        
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
