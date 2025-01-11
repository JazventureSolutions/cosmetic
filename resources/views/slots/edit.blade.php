@extends('layouts.default')
@section('content')
@if (session()->has('success') || session()->has('error'))
    <div class="alert @if(session()->has('error')) alert-danger @else alert-success @endif">
        @if (session()->has('success'))
            {{ session()->get('success') }}
        @else
            {{ session()->get('error') }}
        @endif
    </div>
@endif
<div class="card card-custom">
  <!--begin::Card body-->
  <div class="card-body">
    <form id="kt_form" class="form" method="POST" action="{{ route('slots.store') }}" autoComplete='off'>
      @csrf
      <!--begin::Row-->
      <div class="row">
        <div class="col-md-4">
          <!--begin::Group-->
          <div class="form-group">
            <label class="form-label">Branch</label>
            <select class="form-control form-control-lg {{ $errors->has('branch') ? 'is-invalid' : '' }} patient-select2" name="branch_id" placeholder="Branch" required>
              @if (count($branches))
              @foreach($branches as $branch)
              <option value="{{ $branch->id }}">{{ $branch->name }}</option>
              @endforeach
              @else
              <option value="">Select</option>
              @endif
            </select>
            @if ($errors->has('branch'))
            <span class=" invalid-feedback">{{ $errors->first('branch') }}</span>
            @endif
          </div>
          <!--end::Group-->
        </div>
        <div class="col-md-4">
          <!--begin::Group-->
          <div class="form-group">
            <label class="form-label">Name</label>
            <input name="name" type="text" class="form-control" placeholder="Input Name">
          </div>
          <!--end::Group-->
        </div>
        <div class="col-md-4">
          <!--begin::Group-->
          <div class="form-group">
            <label class="form-label">Appointment Date</label>
            <input class="form-control form-control-lg {{ $errors->has('date') ? 'is-invalid' : '' }}"
              type="text" name="date" placeholder="Appointment Date"
              value="{{ old('date', \Carbon\Carbon::parse($appointment->date ?? '')->format('d.m.Y')) }}" required/>
            @if ($errors->has('date'))
            <span class=" invalid-feedback">{{ $errors->first('date') }}</span>
            @endif
          </div>
          <!--end::Group-->
        </div>

        <div class="col-md-4">
          <!--begin::Group-->
          <div class="form-group">
            <label class="form-label">Slot Start Time</label>
            <input name="start_time" type='time' class="form-control" required />
          </div>
          <!--end::Group-->
        </div>

        <div class="col-md-4">
          <!--begin::Group-->
          <div class="form-group">
            <label class="form-label">Slot End Time</label>
            <input name="end_time" type='time' class="form-control" required />
          </div>
          <!--end::Group-->
        </div>
        <div class="col-md-12">
          <!--begin::Group-->
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="desc" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <!--end::Group-->
        </div>

        <div class="col-md-12">
          <hr>
        </div>
        <div class="col-12 text-right">
          <button class="btn btn-primary btn-lg" type="submit">Submit</button>
        </div>
      </div>
      <!--end::Row-->
    </form>
  </div>
  <!--begin::Card body-->
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
  var AVAILABLE_TIMES_AJAX_URL = '{{ route("patients.appointments.available-times") }}';
  var SELECTED_START_TIME = '{{ old("start_time", $appointment->start_time ?? NULL) }}';
</script>
<script src="https://cdn.tiny.cloud/1/2me38emy24f0gwihie60kmho6as74v8zdoq7vkabuw1egag1/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{ url('/') }}/assets/plugins/custom/datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script>
  $(document).ready(function () {
    $('.patient-select2').select2();

    $('[name="date"]').datetimepicker({
        format: 'd.m.Y',
        timepicker: false,
        mask: true
    });



  });
</script>
@endsection
