@extends('layouts.default')
@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @if (session()->has('slots_error'))
        <div class="alert alert-danger">
            These slots are already Created:
            <ul>
                @foreach (session()->get('slots_error') as $slot_error)
                    <li>{{ $slot_error }}</li>
                @endforeach
            </ul>
        </div>
        @php
            session()->remove('slots_error');
        @endphp
    @endif
    <div class="card card-custom">
        <!--begin::Card body-->
        <div class="card-body">
            <form id="kt_form" class="form" method="POST" enctype="multipart/form-data" action="{{ route('slots.import') }}"
                autoComplete='off'>
                @csrf
                <!--begin::Row-->
                <div class="row">
                    <div class="col-md-6">
                        <input type="file" name="slots">
                    </div>
                    <div class="col-md-6">
                        <a href="https://circumcisionclinic.net/sample/slots.xlsx" class="btn btn-sm btn-info mb-2 mr-2 right" download>Download Sample File For Reference</a>

                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-12 text-right">
                    <button class="btn btn-primary btn-lg" type="submit">Submit</button>
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
