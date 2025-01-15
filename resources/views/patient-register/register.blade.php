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
                IN CASE OF CHILDREN: Please remember both parents must attend clinic on the day of procedure. We need photo ID documents for both parents and ID for the child such as birth certificate, red book, hospital bands, passport, resident permit. We will not perform any procedure if the above are not provided on the day.
            </div>
        </div>

        <form id="kt_form" class="form mt-6" method="POST" action="{{ url()->current() }}">
            @csrf

            <input type="hidden" name="id" value="{{ $patient->id ?? 0 }}">
            {{-- <input type="hidden" name="mode" value="patient"> --}}

            <!--begin::Row-->
            <div class="row">
                {{-- <div class="col-xl-3"></div> --}}
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Patient’s Full name</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            type="text" name="name" placeholder="Patient’s Full name"
                            value="{{ old('name', $patient->name ?? '') }}" required />
                        <span class="text-muted">Please enter full name, including first name, middle name and last name</span>

                        @if ($errors->has('name'))
                        <span class=" invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Date Of Birth</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}"
                            type="date" name="date_of_birth" placeholder="Date Of Birth"
                            value="{{ old('date_of_birth', $patient->date_of_birth ?? '') }}" required />

                        @if ($errors->has('date_of_birth'))
                        <span class=" invalid-feedback">{{ $errors->first('date_of_birth') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Your Weight</label>
                        <div
                            class="input-group input-group-lg {{ $errors->has('weight_of_child') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-weight-hanging"></i>
                                </span>
                            </div>
                            <input type="number"
                                class="form-control form-control-lg {{ $errors->has('weight_of_child') ? 'is-invalid' : '' }}"
                                name="weight_of_child"
                                value="{{ old('weight_of_child', $patient->weight_of_child ?? '') }}"
                                step="any"
                                placeholder="Your Weight" />
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    kg
                                </span>
                            </div>
                        </div>

                        @if ($errors->has('weight_of_child'))
                        <span class=" invalid-feedback">{{ $errors->first('weight_of_child') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Patient Type</label>

                        {{-- <input type="hidden" name="type" value="{{ old('type', $patient->type) }}"> --}}

                        <select class="form-control form-control-lg {{ $errors->has('type') ? 'is-invalid' : '' }} basic-select2"
                            name="type" placeholder="Patient Type" required>
                            @foreach ($patient_types as $typeKey => $type)
                            <option value="{{ $typeKey }}" {{ (old('type', $patient->type) == $typeKey) ? 'selected' : '' }}>
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
                <div class="col-md-12">
                    <hr>
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
                                name="cell_number" value="{{ old('cell_number', $patient->cell_number ?? '') }}"
                                placeholder="Mobile" required />
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
                                name="phone" value="{{ old('phone', $patient->phone ?? '') }}"
                                placeholder="House Telephone" required/>
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
                                name="email" value="{{ old('email', $patient->email ?? '') }}"
                                placeholder="Email Address" required/>
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
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">House #</label>
                        <div class="input-group input-group-lg {{ $errors->has('house_no') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-home"></i>
                                </span>
                            </div>
                            <input type="text"
                                class="form-control form-control-lg {{ $errors->has('house_no') ? 'is-invalid' : '' }}"
                                name="house_no" value="{{ old('house_no', $patient->house_no ?? '') }}"
                                placeholder="House #" required/>
                        </div>

                        @if ($errors->has('house_no'))
                        <span class=" invalid-feedback">{{ $errors->first('house_no') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Street/Road Name</label>
                        <div class="input-group input-group-lg {{ $errors->has('street') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-home"></i>
                                </span>
                            </div>
                            <input type="text"
                                class="form-control form-control-lg {{ $errors->has('street') ? 'is-invalid' : '' }}"
                                name="street" value="{{ old('street', $patient->street ?? '') }}"
                                placeholder="Street/Road Name" required/>
                        </div>

                        @if ($errors->has('street'))
                        <span class=" invalid-feedback">{{ $errors->first('street') }}</span>
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
                                name="city" value="{{ old('city', $patient->city ?? '') }}"
                                placeholder="Town/City" required/>
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
                        <label class="form-label">Post Code</label>
                        <div class="input-group input-group-lg {{ $errors->has('post_code') ? 'is-invalid' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-home"></i>
                                </span>
                            </div>
                            <input type="text"
                                class="form-control form-control-lg {{ $errors->has('post_code') ? 'is-invalid' : '' }}"
                                name="post_code" value="{{ old('post_code', $patient->post_code ?? '') }}"
                                placeholder="Post Code" required/>
                        </div>

                        @if ($errors->has('post_code'))
                        <span class=" invalid-feedback">{{ $errors->first('post_code') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-6 hide-new_born hide-old_boy">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Next of Kin</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('next_kin') ? 'is-invalid' : '' }}"
                            type="text" name="next_kin" placeholder="Next of Kin"
                            value="{{ old('next_kin', $patient->next_kin ?? '') }}" />

                        @if ($errors->has('next_kin'))
                        <span class=" invalid-feedback">{{ $errors->first('next_kin') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6 hide-new_born hide-old_boy">
                <div class="form-group">
                    <label class="form-label">Next of Kin Relationship</label>
                    <input
                        class="form-control form-control-lg {{ $errors->has('next_kin_relationship') ? 'is-invalid' : '' }}"
                        type="text" name="next_kin_relationship" placeholder="Next of Kin Relationship "
                        value="{{ old('next_kin_relationship', $patient->next_kin_relationship ?? '') }}" />


                    @if ($errors->has('next_kin_relationship'))
                    <span class=" invalid-feedback">{{ $errors->first('next_kin_relationship') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6 hide-new_born hide-old_boy">
                <div class="form-group">
                    <label class="form-label">Next of Kin Address</label>
                    <input
                        class="form-control form-control-lg {{ $errors->has('next_kin_address') ? 'is-invalid' : '' }}"
                        type="text" name="next_kin_address" placeholder="Next of Kin Address "
                        value="{{ old('next_kin_address', $patient->next_kin_address ?? '') }}" />


                    @if ($errors->has('next_kin_address'))
                    <span class=" invalid-feedback">{{ $errors->first('next_kin_address') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6 hide-new_born hide-old_boy">
                <div class="form-group">
                    <label class="form-label">Next of Kin Mobile</label>
                    <input
                        class="form-control form-control-lg {{ $errors->has('next_kin_phone') ? 'is-invalid' : '' }}"
                        type="text" name="next_kin_phone" placeholder="Next of Kin Mobile "
                        value="{{ old('next_kin_phone', $patient->next_kin_phone ?? '') }}" />


                    @if ($errors->has('next_kin_phone'))
                    <span class=" invalid-feedback">{{ $errors->first('next_kin_phone') }}</span>
                    @endif
                </div>
                </div>
                <div class="col-md-6 hide-adult">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Father Name</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('father_name') ? 'is-invalid' : '' }}"
                            type="text" name="father_name" placeholder="Father Name"
                            value="{{ old('father_name', $patient->father_name ?? '') }}"/>
                        <span class="text-muted">Please enter full name, including first name, middle name and last name</span>

                        @if ($errors->has('father_name'))
                        <span class=" invalid-feedback">{{ $errors->first('father_name') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-6 hide-adult">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Mother Name</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('mother_name') ? 'is-invalid' : '' }}"
                            type="text" name="mother_name" placeholder="Mother Name"
                            value="{{ old('mother_name', $patient->mother_name ?? '') }}"/>
                        <span class="text-muted">Please enter full name, including first name, middle name and last name</span>

                        @if ($errors->has('mother_name'))
                        <span class=" invalid-feedback">{{ $errors->first('mother_name') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-12">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">GP Details</label>
                        <textarea name="gp_details"
                            class="form-control form-control-lg {{ $errors->has('gp_details') ? 'is-invalid' : '' }}" required>{{ old('gp_details', $patient->gp_details ?? '') }}</textarea>

                        @if ($errors->has('gp_details'))
                        <span class=" invalid-feedback">{{ $errors->first('gp_details') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Appointment Type</label>

                        {{-- <input type="hidden" name="type" value="{{ old('type', $patient->type) }}"> --}}

                        <select class="form-control form-control-lg {{ $errors->has('appoint_type') ? 'is-invalid' : '' }} basic-select2"
                            name="appoint_type" placeholder="Appointment Type" required>
                            <option value="">Select</option>
                            @foreach ($AppointmentTypes as $appointtypeKey => $appoint_type)
                            <option value="{{ $appointtypeKey }}" {{ (old('appoint_type', $patient->appoint_type) == $appointtypeKey) ? 'selected' : '' }}>
                                {{ $appoint_type }}
                            </option>
                            @endforeach
                        </select>

                        @if ($errors->has('appoint_type'))
                        <span class=" invalid-feedback">{{ $errors->first('appoint_type') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->
                </div>
                <div class="col-md-6">
                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Appointment Reason</label>
                        <textarea name="Appoint_reason"
                        class="form-control form-control-lg {{ $errors->has('Appoint_reason') ? 'is-invalid' : '' }}" required>{{ old('Appoint_reason', $patient->Appoint_reason ?? '') }}</textarea>

                    @if ($errors->has('Appoint_reason'))
                    <span class=" invalid-feedback">{{ $errors->first('Appoint_reason') }}</span>
                    @endif
                    </div>
                    <!--end::Group-->
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group {{ $errors->has('branch_id') ? 'is-invalid' : '' }}">
                        <label class="form-label">Appointment City</label>

                        <select class="form-control form-control-lg {{ $errors->has('branch_id') ? 'is-invalid' : '' }} basic-select2"
                            name="branch_id" placeholder="Appointment City" required>
                            <option value="">Select</option>
                            @foreach ($branches as $branchKey => $branch)
                            <option value="{{ $branchKey }}" {{ (old('branch_id', $patient->branch_id) == $branchKey) ? 'selected' : '' }}>{{ $branch }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('branch_id'))
                        <span class=" invalid-feedback">The Appointment City is required.</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>

                {{-- @if ($patient->approved == "1") --}}
                <div class="col-md-12 my-2">

                    <!--begin::Group-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>

                </div>
                {{-- @endif --}}
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
        onUserTypeChange();
    });

    function onUserTypeChange () {
        var selected_type = $('[name="type"]').val() ?? 'adult';
        switch (selected_type) {
            case 'adult':
                $('.hide-adult').hide();
                $('.hide-old_boy').show();
                $('.hide-new_born').show();
                //
                $('.hide-adult input').removeAttr('required');
                $('.hide-old_boy input').attr('required', true);
                $('.hide-new_born input').attr('required', true);
                break;

            default:
                $('.hide-adult').show();
                $('.hide-old_boy').hide();
                $('.hide-new_born').hide();
                //
                $('.hide-adult input').attr('required', true);
                 $('.hide-old_boy input').removeAttr('required');
                 $('.hide-new_born input').removeAttr('required');
                break;
        }
    }

    $(document).on('change', '[name="type"]', onUserTypeChange);
</script>

@endsection
