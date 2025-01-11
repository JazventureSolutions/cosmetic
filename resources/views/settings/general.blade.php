@extends('layouts.default')

@section('content')

<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">General
                <span class="d-block text-muted pt-2 font-size-sm">Sorting &amp; pagination remote datasource</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <button type="reset" class="btn btn-primary mr-2">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
    <div class="card-body">
        <form class="form">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="email" class="form-control form-control-solid" placeholder="Enter full name" />
                <span class="form-text text-muted">Please enter your full name</span>
            </div>
            <div class="form-group">
                <label>Email address:</label>
                <input type="email" class="form-control form-control-solid" placeholder="Enter email" />
                <span class="form-text text-muted">We'll never share your email with anyone else</span>
            </div>
            <div class="form-group">
                <label>Subscription</label>
                <div class="input-group input-group-lg">
                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                    <input type="text" class="form-control form-control-solid" placeholder="99.9" />
                </div>
            </div>
            <div class="form-group">
                <label>Communication:</label>
                <div class="checkbox-list">
                    <label class="checkbox">
                        <input type="checkbox" />
                        <span></span>
                        Email
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" />
                        <span></span>
                        SMS
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" />
                        <span></span>
                        Phone
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>
<!--end::Card-->

@endsection


@section('styles')

@endsection


@section('scripts')

@endsection
