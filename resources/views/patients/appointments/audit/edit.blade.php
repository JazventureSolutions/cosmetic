@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <!--begin::Card body-->
    <div class="card-body">

        @php
            // dump($audit);
        @endphp

        <form id="kt_form" class="form" method="POST" action="{{ route('patients.appointments.audit.save', ['appointment_id' => $appointment->id ?? 0]) }}">
            @csrf

            <input type="hidden" name="id" value="{{ $appointment->id ?? 0 }}">
            <input type="hidden" name="appointment_id" value="{{ $appointment->id ?? 0 }}">
            <input type="hidden" name="patient_id" value="{{ $patient->id ?? 0 }}">

            <!--begin::Row-->
            <div class="row">
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Patient</label>
                        <input class="form-control form-control-lg {{ $errors->has('patient') ? 'is-invalid' : '' }}"
                            type="text" name="patient" placeholder="Patient"
                            value="{{ $patient->name }}" readonly/>

                        @if ($errors->has('patient'))
                        <span class=" invalid-feedback">{{ $errors->first('patient') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-2">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Appointment Date</label>
                        <input class="form-control form-control-lg {{ $errors->has('date') ? 'is-invalid' : '' }}"
                            type="text" name="date" placeholder="Appointment Date"
                            value="{{ old('date', \Carbon\Carbon::parse($appointment->date ?? '')->format('d.m.Y')) }}" readonly/>

                        @if ($errors->has('date'))
                        <span class=" invalid-feedback">{{ $errors->first('date') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-2">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Appointment Time</label>
                        <input class="form-control form-control-lg {{ $errors->has('start_time') ? 'is-invalid' : '' }}"
                            type="text" name="start_time" placeholder="Appointment Time"
                            value="{{ old('start_time', $appointment->start_time ?? '') }}" readonly/>

                        @if ($errors->has('start_time'))
                        <span class=" invalid-feedback">{{ $errors->first('start_time') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-2">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Follow Up Date</label>
                        <input class="form-control form-control-lg {{ $errors->has('followup_date') ? 'is-invalid' : '' }}"
                            type="text" name="followup_date" placeholder="Follow Up Date"
                            value="{{ old('followup_date', $appointment->followup_date ? \Carbon\Carbon::parse($appointment->followup_date)->format('d.m.Y') : '') }}" readonly/>

                        @if ($errors->has('followup_date'))
                        <span class=" invalid-feedback">{{ $errors->first('followup_date') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Any Other</label>
                        <input class="form-control form-control-lg {{ $errors->has('any_other') ? 'is-invalid' : '' }}"
                            type="text" name="any_other" placeholder="Any Other"
                            value="{{ old('any_other', $audit->any_other ?? '') }}" />

                        @if ($errors->has('any_other'))
                        <span class=" invalid-feedback">{{ $errors->first('any_other') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Adhesion</label>
                        <input class="form-control form-control-lg {{ $errors->has('adhesion') ? 'is-invalid' : '' }}"
                            type="text" name="adhesion" placeholder="Adhesion"
                            value="{{ old('adhesion', $audit->adhesion ?? '') }}" />

                        @if ($errors->has('adhesion'))
                        <span class=" invalid-feedback">{{ $errors->first('adhesion') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Bleeding</label>
                        <input class="form-control form-control-lg {{ $errors->has('bleeding') ? 'is-invalid' : '' }}"
                            type="text" name="bleeding" placeholder="Bleeding"
                            value="{{ old('bleeding', $audit->bleeding ?? '') }}" />

                        @if ($errors->has('bleeding'))
                        <span class=" invalid-feedback">{{ $errors->first('bleeding') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Circumplast</label>
                        <input class="form-control form-control-lg {{ $errors->has('circumplast') ? 'is-invalid' : '' }}"
                            type="text" name="circumplast" placeholder="Circumplast"
                            value="{{ old('circumplast', $audit->circumplast ?? '') }}" />

                        @if ($errors->has('circumplast'))
                        <span class=" invalid-feedback">{{ $errors->first('circumplast') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Consultation Only</label>
                        <input class="form-control form-control-lg {{ $errors->has('consultation_only') ? 'is-invalid' : '' }}"
                            type="text" name="consultation_only" placeholder="Not Done"
                            value="{{ old('consultation_only', $audit->consultation_only ?? '') }}" />

                        @if ($errors->has('consultation_only'))
                        <span class=" invalid-feedback">{{ $errors->first('consultation_only') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Curve</label>
                        <input class="form-control form-control-lg {{ $errors->has('curve') ? 'is-invalid' : '' }}"
                            type="text" name="curve" placeholder="curve"
                            value="{{ old('curve', $audit->curve ?? '') }}" />

                        @if ($errors->has('curve'))
                        <span class=" invalid-feedback">{{ $errors->first('curve') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Chordee</label>
                        <input class="form-control form-control-lg {{ $errors->has('chordee') ? 'is-invalid' : '' }}"
                            type="text" name="chordee" placeholder="chordee"
                            value="{{ old('chordee', $audit->chordee ?? '') }}" />

                        @if ($errors->has('chordee'))
                        <span class=" invalid-feedback">{{ $errors->first('chordee') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Comments</label>
                        <input class="form-control form-control-lg {{ $errors->has('comments') ? 'is-invalid' : '' }}"
                            type="text" name="comments" placeholder="Comments"
                            value="{{ old('comments', $audit->comments ?? '') }}" />

                        @if ($errors->has('comments'))
                        <span class=" invalid-feedback">{{ $errors->first('comments') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">DNA</label>
                        <input class="form-control form-control-lg {{ $errors->has('dna') ? 'is-invalid' : '' }}"
                            type="text" name="dna" placeholder="DNA"
                            value="{{ old('dna', $audit->dna ?? '') }}" />

                        @if ($errors->has('dna'))
                        <span class=" invalid-feedback">{{ $errors->first('dna') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Division of Adhesion</label>
                        <input class="form-control form-control-lg {{ $errors->has('division_of_adhesion') ? 'is-invalid' : '' }}"
                            type="text" name="division_of_adhesion" placeholder="Division of Adhesion"
                            value="{{ old('division_of_adhesion', $audit->division_of_adhesion ?? '') }}" />

                        @if ($errors->has('division_of_adhesion'))
                        <span class=" invalid-feedback">{{ $errors->first('division_of_adhesion') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">FU</label>
                        <input class="form-control form-control-lg {{ $errors->has('followup') ? 'is-invalid' : '' }}"
                            type="text" name="followup" placeholder="FU"
                            value="{{ old('followup', $audit->followup ?? '') }}" />

                        @if ($errors->has('followup'))
                        <span class=" invalid-feedback">{{ $errors->first('followup') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Fat</label>
                        <input class="form-control form-control-lg {{ $errors->has('fat') ? 'is-invalid' : '' }}"
                            type="text" name="fat" placeholder="Fat"
                            value="{{ old('fat', $audit->fat ?? '') }}" />

                        @if ($errors->has('fat'))
                        <span class=" invalid-feedback">{{ $errors->first('fat') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Frenuloplasty</label>
                        <input class="form-control form-control-lg {{ $errors->has('frenuloplasty') ? 'is-invalid' : '' }}"
                            type="text" name="frenuloplasty" placeholder="frenuloplasty"
                            value="{{ old('frenuloplasty', $audit->frenuloplasty ?? '') }}" />

                        @if ($errors->has('frenuloplasty'))
                        <span class=" invalid-feedback">{{ $errors->first('frenuloplasty') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->
                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Hypospediasis</label>
                        <input class="form-control form-control-lg {{ $errors->has('hypospediasis') ? 'is-invalid' : '' }}"
                            type="text" name="hypospediasis" placeholder="Hypospediasis"
                            value="{{ old('hypospediasis', $audit->hypospediasis ?? '') }}" />

                        @if ($errors->has('hypospediasis'))
                        <span class=" invalid-feedback">{{ $errors->first('hypospediasis') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Hypopaedias</label>
                        <input class="form-control form-control-lg {{ $errors->has('hypopaedias') ? 'is-invalid' : '' }}"
                            type="text" name="hypopaedias" placeholder="Hypopaedias"
                            value="{{ old('hypopaedias', $audit->hypopaedias ?? '') }}" />

                        @if ($errors->has('hypopaedias'))
                        <span class=" invalid-feedback">{{ $errors->first('hypopaedias') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Hydrocoele</label>
                        <input class="form-control form-control-lg {{ $errors->has('hydrocoele') ? 'is-invalid' : '' }}"
                            type="text" name="hydrocoele" placeholder="Hydrocoele"
                            value="{{ old('hydrocoele', $audit->hydrocoele ?? '') }}" />

                        @if ($errors->has('hydrocoele'))
                        <span class=" invalid-feedback">{{ $errors->first('hydrocoele') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Infection</label>
                        <input class="form-control form-control-lg {{ $errors->has('infection') ? 'is-invalid' : '' }}"
                            type="text" name="infection" placeholder="Infection"
                            value="{{ old('infection', $audit->infection ?? '') }}" />

                        @if ($errors->has('infection'))
                        <span class=" invalid-feedback">{{ $errors->first('infection') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Not Done</label>
                        <input class="form-control form-control-lg {{ $errors->has('not_done') ? 'is-invalid' : '' }}"
                            type="text" name="not_done" placeholder="Not Done"
                            value="{{ old('not_done', $audit->not_done ?? '') }}" />

                        @if ($errors->has('not_done'))
                        <span class=" invalid-feedback">{{ $errors->first('not_done') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>

                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Not Required</label>
                        <input class="form-control form-control-lg {{ $errors->has('not_required') ? 'is-invalid' : '' }}"
                            type="text" name="not_required" placeholder="Not Done"
                            value="{{ old('not_required', $audit->not_required ?? '') }}" />

                        @if ($errors->has('not_required'))
                        <span class=" invalid-feedback">{{ $errors->first('not_required') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Oral</label>
                        <input class="form-control form-control-lg {{ $errors->has('oral') ? 'is-invalid' : '' }}"
                            type="text" name="oral" placeholder="Oral"
                            value="{{ old('oral', $audit->oral ?? '') }}" />

                        @if ($errors->has('oral'))
                        <span class=" invalid-feedback">{{ $errors->first('oral') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Peripubic fat</label>
                        <input class="form-control form-control-lg {{ $errors->has('peripubic_fat') ? 'is-invalid' : '' }}"
                            type="text" name="peripubic_fat" placeholder="Peripubic fat"
                            value="{{ old('peripubic_fat', $audit->peripubic_fat ?? '') }}" />

                        @if ($errors->has('peripubic_fat'))
                        <span class=" invalid-feedback">{{ $errors->first('peripubic_fat') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>

                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Plastibell</label>
                        <input class="form-control form-control-lg {{ $errors->has('plastibell') ? 'is-invalid' : '' }}"
                            type="text" name="plastibell" placeholder="Plastibell"
                            value="{{ old('plastibell', $audit->plastibell ?? '') }}" />

                        @if ($errors->has('plastibell'))
                        <span class=" invalid-feedback">{{ $errors->first('plastibell') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Resection</label>
                        <input class="form-control form-control-lg {{ $errors->has('resection') ? 'is-invalid' : '' }}"
                            type="text" name="resection" placeholder="Resection"
                            value="{{ old('resection', $audit->resection ?? '') }}" />

                        @if ($errors->has('resection'))
                        <span class=" invalid-feedback">{{ $errors->first('resection') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>

                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Retained Plastibell</label>
                        <input class="form-control form-control-lg {{ $errors->has('retained_plastibell') ? 'is-invalid' : '' }}"
                            type="text" name="retained_plastibell" placeholder="Retained Plastibell"
                            value="{{ old('retained_plastibell', $audit->retained_plastibell ?? '') }}" />

                        @if ($errors->has('retained_plastibell'))
                        <span class=" invalid-feedback">{{ $errors->first('retained_plastibell') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>


                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Residual Skin</label>
                        <input class="form-control form-control-lg {{ $errors->has('residual_skin') ? 'is-invalid' : '' }}"
                            type="text" name="residual_skin" placeholder="Residual Skin"
                            value="{{ old('residual_skin', $audit->residual_skin ?? '') }}" />

                        @if ($errors->has('residual_skin'))
                        <span class=" invalid-feedback">{{ $errors->first('residual_skin') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Revisions Follow Ups</label>
                        <input class="form-control form-control-lg {{ $errors->has('revisions_follow_ups') ? 'is-invalid' : '' }}"
                            type="text" name="revisions_follow_ups" placeholder="Revisions Follow Ups"
                            value="{{ old('revisions_follow_ups', $audit->revisions_follow_ups ?? '') }}" />

                        @if ($errors->has('revisions_follow_ups'))
                        <span class=" invalid-feedback">{{ $errors->first('revisions_follow_ups') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Redundant Inner Skin</label>
                        <input class="form-control form-control-lg {{ $errors->has('redundant_inner_skin') ? 'is-invalid' : '' }}"
                            type="text" name="redundant_inner_skin" placeholder="Redundant Inner Skin"
                            value="{{ old('redundant_inner_skin', $audit->redundant_inner_skin ?? '') }}" />

                        @if ($errors->has('redundant_inner_skin'))
                        <span class=" invalid-feedback">{{ $errors->first('redundant_inner_skin') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Secondary Phimosis</label>
                        <input class="form-control form-control-lg {{ $errors->has('secondary_phimosis') ? 'is-invalid' : '' }}"
                            type="text" name="secondary_phimosis" placeholder="Secondary Phimosis"
                            value="{{ old('secondary_phimosis', $audit->secondary_phimosis ?? '') }}" />

                        @if ($errors->has('secondary_phimosis'))
                        <span class=" invalid-feedback">{{ $errors->first('secondary_phimosis') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Soft adhesion</label>
                        <input class="form-control form-control-lg {{ $errors->has('soft_adhesion') ? 'is-invalid' : '' }}"
                            type="text" name="soft_adhesion" placeholder="Soft adhesion"
                            value="{{ old('soft_adhesion', $audit->soft_adhesion ?? '') }}" />

                        @if ($errors->has('soft_adhesion'))
                        <span class=" invalid-feedback">{{ $errors->first('soft_adhesion') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>


                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Torsion</label>
                        <input class="form-control form-control-lg {{ $errors->has('torsion') ? 'is-invalid' : '' }}"
                            type="text" name="torsion" placeholder="Torsion"
                            value="{{ old('torsion', $audit->torsion ?? '') }}" />

                        @if ($errors->has('torsion'))
                        <span class=" invalid-feedback">{{ $errors->first('torsion') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>




                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Topical</label>
                        <input class="form-control form-control-lg {{ $errors->has('topical') ? 'is-invalid' : '' }}"
                            type="text" name="topical" placeholder="Topical"
                            value="{{ old('topical', $audit->topical ?? '') }}" />

                        @if ($errors->has('topical'))
                        <span class=" invalid-feedback">{{ $errors->first('topical') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>




                <div class="col-md-3">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Without father</label>
                        <input class="form-control form-control-lg {{ $errors->has('without_father') ? 'is-invalid' : '' }}"
                            type="text" name="without_father" placeholder="Without father"
                            value="{{ old('without_father', $audit->without_father ?? '') }}" />

                        @if ($errors->has('without_father'))
                        <span class=" invalid-feedback">{{ $errors->first('without_father') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>

                <div class="col-md-3">
                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Webbed Penis</label>
                        <input class="form-control form-control-lg {{ $errors->has('webbed_penis') ? 'is-invalid' : '' }}"
                            type="text" name="webbed_penis" placeholder="Webbed Penis"
                            value="{{ old('webbed_penis', $audit->webbed_penis ?? '') }}" />

                        @if ($errors->has('webbed_penis'))
                        <span class=" invalid-feedback">{{ $errors->first('webbed_penis') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12 my-2">

                    <!--begin::Group-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </div>

                </div>
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

@endsection
