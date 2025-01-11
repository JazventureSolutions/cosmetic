@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <!--begin::Card body-->
    <div class="card-body">

        <form id="kt_form" class="form" method="POST" action="{{ route('patient-register.store') }}">
            @csrf

            <input type="hidden" name="id" value="{{ $patient_inq->id ?? 0 }}">
            <input type="hidden" name="form_type" value="submit">
            {{-- form_type: submit/sms --}}

            <!--begin::Row-->
            <div class="row">
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Cell Number</label>
                        <div
                            class="input-group input-group-lg {{ $errors->has('cell_number') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-phone"></i> +44
                                </span>
                            </div>
                            <input type="tel"
                                class="form-control form-control-lg {{ $errors->has('cell_number') ? 'is-invalid' : '' }}"
                                name="cell_number" value="{{ old('cell_number', $patient_inq->cell_number ?? '') }}"
                                placeholder="Cell Number" />
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
                        <label class="form-label">Email Address</label>
                        <div class="input-group input-group-lg {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-at"></i>
                                </span>
                            </div>
                            <input type="email"
                                class="form-control form-control-lg {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                name="email" value="{{ old('email', $patient_inq->email ?? '') }}"
                                placeholder="Email Address" />
                        </div>

                        @if ($errors->has('email'))
                        <span class=" invalid-feedback">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-4">
                     <!--begin::Group-->
                     <div class="form-group">
                         <input type="checkbox" name="remote_patent_status" value="1"  @if($patient_inq->remote_patent_status == 1) checked @endif>
                        <label class="form-label">Remote Patent Status</label>
                    </div>
                    <!--end::Group-->
                </div>
                <div class="col-md-12">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Register Link</label>
                        <input disabled
                            class="form-control form-control-lg {{ $errors->has('link') ? 'is-invalid' : '' }}"
                            type="text" name="link" placeholder="Register Link"
                            value="{{ old('link', $patient_inq->link ?? '') }}" required />

                        @if ($errors->has('link'))
                        <span class=" invalid-feedback">{{ $errors->first('link') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Register Link</label>
                        <div class="form-control form-control-lg" style="height: auto; background-color: rgb(243, 246, 249)">
                            <p>
                                Circumcision Clinic. The following details are needed to book an appointment, Kindly submit your details through this link: <br>
                                {{ old('link', $patient_inq->link ?? '') }} <br>
                                {{-- <br> --}}
                                {{-- IN CASE OF CHILDREN: Please remember both parents must attend clinic on the day of procedure. We need photo ID documents for both parents and ID for the child such as birth certificate, red book, hospital bands, passport, resident permit. We will not perform any procedure if the above are not provided on the day. <br> --}}
                                <br>
                                Thanks. <br>
                                Dr Anwar Khan
                            </p>
                        </div>

                        @if ($errors->has('link'))
                        <span class=" invalid-feedback">{{ $errors->first('link') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                {{-- @if ($patient_inq->patient_id == null) --}}
                <div class="col-md-12 my-2">

                    <!--begin::Group-->
                    <div class="form-group">
                        @if ($patient_inq->patient_id == null)
                        <button type="submit" class="btn btn-primary mr-2">{{ $patient_inq->id == 0 ? 'Generate' : 'Regenerate' }} Link</button>
                        @endif
                        @if ($patient_inq->id > 0)
                        <button type="submit" class="btn btn-secondary mr-2 btn-send-sms">Send SMS</button>
                        <button type="submit" class="btn btn-secondary mr-2 btn-send-email">Send Email</button>
                        <button type="submit" class="btn btn-secondary mr-2 btn-send-sms_email">Send SMS & Email</button>
                        @endif
                    </div>

                </div>
                {{-- @endif --}}
                @if ($patient_inq->sms_sent_at)
                <div class="col-md-12 my-2">
                    Last SMS Sent on <b>{{ \Carbon\Carbon::parse($patient_inq->sms_sent_at)->toFormattedDateString() . ' ' . \Carbon\Carbon::parse($patient_inq->sms_sent_at)->toTimeString() }}</b>
                </div>
                @endif
                @if ($patient_inq->email_sent_at)
                <div class="col-md-12 my-2">
                    Last SMS Sent on <b>{{ \Carbon\Carbon::parse($patient_inq->sms_sent_at)->toFormattedDateString() . ' ' . \Carbon\Carbon::parse($patient_inq->sms_sent_at)->toTimeString() }}</b>
                </div>
                @endif
            </div>
            <!--end::Row-->

        </form>

    </div>
    <!--begin::Card body-->
</div>

@endsection


@section('styles')

@endsection


@section('scripts')

<script>
    $(document).ready(function () {
        $('.patient-select2').select2({
            ajax: {
                url: '{{ route("patients.select2") }}',
                dataType: 'json'
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });

        $(document).on('click', 'form [type=submit]', function (e) {
            e.preventDefault();

            if ($(this).hasClass('btn-send-sms')) {
                $('[name="form_type"]').val('sms');
            } else if ($(this).hasClass('btn-send-email')) {
                $('[name="form_type"]').val('email');
            } else if ($(this).hasClass('btn-send-sms_email')) {
                $('[name="form_type"]').val('sms_email');
            } else {
                $('[name="form_type"]').val('submit');
            }

            $(this).closest('form').submit();
        });
    });
</script>

@endsection
