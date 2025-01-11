@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <!--begin::Card header-->
    <div class="card-header card-header-tabs-line nav-tabs-line-3x">
        <!--begin::Toolbar-->
        <div class="card-toolbar">
            <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                <!--begin::Item-->
                <li class="nav-item mr-3">
                    <a class="nav-link {{ old('mode', $mode ?? 'patient') == 'patient' ? 'active' : '' }}"
                        data-toggle="tab" href="#tab_patient">
                        <span class="nav-icon">
                            <span class="svg-icon">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                        <span class="nav-text font-size-lg">Patient</span>
                    </a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="nav-item">
                    <a class="nav-link {{ old('mode', $mode ?? 'patient') == 'appointments' ? 'active' : '' }}"
                        data-toggle="tab" href="#tab_appointments">
                        <span class="nav-icon">
                            <span class="svg-icon">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z"
                                            fill="#000000" opacity="0.3" />
                                        <path
                                            d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z"
                                            fill="#000000" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                        <span class="nav-text font-size-lg">Appointments</span>
                    </a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="nav-item">
                    <a class="nav-link {{ old('mode', $mode ?? 'patient') == 'similar' ? 'active' : '' }}"
                        data-toggle="tab" href="#tab_similar">
                        <span class="nav-icon">
                            <span class="svg-icon">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                        <span class="nav-text font-size-lg">Similar Patients</span>
                    </a>
                </li>
                <!--end::Item-->
            </ul>
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body">
        <div class="tab-content">
            <!--begin::Tab-->
            <div class="tab-pane {{ old('mode', $mode ?? 'patient') == 'patient' ? 'show active' : '' }}"
                id="tab_patient" role="tabpanel">

                <form id="patient_form" class="form" method="POST" action="{{ route('patients.store') }}">
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
                                    value="{{ old('date_of_birth', $patient->date_of_birth ?? '') }}" />

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
                                    name="type" placeholder="Patient Type">
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
                                        name="phone" value="{{ old('phone', $patient->phone ?? '') }}"
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
                                        name="email" value="{{ old('email', $patient->email ?? '') }}"
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
                                        placeholder="House #" />
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
                                        placeholder="Street/Road Name" />
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
                                        placeholder="Post Code" />
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
                                    value="{{ old('father_name', $patient->father_name ?? '') }}" />

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
                                    value="{{ old('mother_name', $patient->mother_name ?? '') }}" />

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
                                    class="form-control form-control-lg {{ $errors->has('gp_details') ? 'is-invalid' : '' }}">{{ old('gp_details', $patient->gp_details ?? '') }}</textarea>

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
                            <div class="form-group">
                                <label class="form-label">Appointment City</label>

                                <select class="form-control form-control-lg {{ $errors->has('type') ? 'is-invalid' : '' }} basic-select2"
                                    name="branch_id" placeholder="Appointment City">
                                    <option value="">Select</option>
                                    @foreach ($branches as $branchKey => $branch)
                                    <option value="{{ $branchKey }}" {{ ((old('branch_id', $patient->branch_id) ?? Auth::user()->branch_id) == $branchKey) ? 'selected' : '' }}>{{ $branch }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('branch_id'))
                                <span class=" invalid-feedback">{{ $errors->first('branch_id') }}</span>
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
            <!--end::Tab-->

            <!--begin::Tab-->
            <div class="tab-pane {{ old('mode', $mode ?? 'patient') == 'appointments' ? 'show active' : '' }}"
                id="tab_appointments" role="tabpanel" data-patient_id="{{ $patient->id ?? 0 }}">
                {{-- AJAX LOAD APPOINTMENTS --}}
            </div>

            <!--begin::Tab-->
            <div class="tab-pane {{ old('mode', $mode ?? 'patient') == 'similar' ? 'show active' : '' }}"
                id="tab_similar" role="tabpanel">
                {{-- AJAX LOAD SIMILAR --}}
            </div>
        </div>
    </div>
    <!--begin::Card body-->
</div>

@endsection


@section('styles')

@endsection


@section('scripts')

<script>
    var APPOINTMENTS_AJAX_URL = '{{ route("patients.appointments") }}';
    var SIMILAR_PATIENTS_AJAX_URL = '{{ route("patients.similar") }}';
    var APPOINTMENTS_PAGE = 1;

    $(document).ready(function () {
        var patient_id = $('#tab_appointments').data('patient_id');
        fetchAppointments(patient_id, APPOINTMENTS_PAGE);
        onUserTypeChange();
    });

    function onUserTypeChange () {
        var selected_type = $('[name="type"]').val() ?? 'adult';
        switch (selected_type) {
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
    }

    $(document).on('change', '[name="type"]', onUserTypeChange);

    function fetchAppointments(patient_id, page_no) {
        blockPage();

        $.ajax({
            type: 'GET',
            url: APPOINTMENTS_AJAX_URL + '/?patient_id=' + patient_id + '&page=' + page_no,
            success: function (data) {
                unblockPage();

                // console.log(data.html);

                // append data
                $('#tab_appointments').html(data.html);
            },
            error: function (error) {
                unblockPage()

                showErrorAlert(error.responseJSON.message, () => {
                    KTUtil.scrollTop();
                });
            }
        });
    }

    // $(document).on('click', '.pagination a', function (event) {
    //     event.preventDefault();

    //     $('li').removeClass('active');
    //     $(this).parent('li').addClass('active');

    //     var myurl = $(this).attr('href');
    //     _page = $(this).attr('href').split('page=')[1];

    //     var patient_id = $('#tab_appointments').data('patient_id');
    //     fetchAppointments(patient_id, _page);
    // });

    function findSimilarPatients() {
        var patient_id = $('#patient_form [name="id"]').val();
        var patient_name = $('#patient_form [name="name"]').val();
        var patient_cell_number = $('#patient_form [name="cell_number"]').val();
        var patient_phone = $('#patient_form [name="phone"]').val();
        var patient_email = $('#patient_form [name="email"]').val();

        blockPage();

        $.ajax({
            type: 'GET',
            url: SIMILAR_PATIENTS_AJAX_URL + '/?id=' + patient_id + '&name=' + patient_name + '&cell_number=' + patient_cell_number + '&phone=' + patient_phone + '&email=' + patient_email,
            success: function (data) {
                unblockPage();

                // console.log(data);

                // append data
                $('#tab_similar').html(data.html);
                $('[href="#tab_similar"] span.nav-text')
                    .html('Similar Patients (' + data.count + ')');
            },
            error: function (error) {
                unblockPage()

                showErrorAlert(error.responseJSON.message, () => {
                    KTUtil.scrollTop();
                });
            }
        });
    }

    $(document).on('click', '[href="#tab_similar"]', findSimilarPatients);
</script>

@endsection
