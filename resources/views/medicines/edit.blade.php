@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <!--begin::Card body-->
    <div class="card-body">

        <form id="kt_form" class="form" method="POST" action="{{ route('settings.medicines.store') }}">
            @csrf

            <input type="hidden" name="id" value="{{ $medicine->id ?? 0 }}">

            <!--begin::Row-->
            <div class="row">
                <div class="col-md-12">

                    <!--begin::Group-->
                    <h1>{{ $medicine->name }}</h1>
                    <!--end::Group-->

                </div>
                <div class="col-md-4">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Medicine Batch No.</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('batch') ? 'is-invalid' : '' }}"
                            type="text" name="batch" placeholder="Medicine Batch No."
                            value="{{ old('batch', $medicine->batch ?? '') }}" required />

                        @if ($errors->has('batch'))
                        <span class=" invalid-feedback">{{ $errors->first('batch') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-4">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Medicine Expiry</label>
                        <input
                            class="form-control form-control-lg {{ $errors->has('expiry') ? 'is-invalid' : '' }}"
                            type="text" name="expiry" placeholder="Medicine Expiry"
                            value="{{ old('expiry', $medicine->expiry ?? '') }}" required />

                        @if ($errors->has('expiry'))
                        <span class=" invalid-feedback">{{ $errors->first('expiry') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12">
                    <hr>
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
