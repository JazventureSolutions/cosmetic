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
                    <a class="nav-link {{ old('mode', $mode ?? 'profile') == 'profile' ? 'active' : '' }}"
                        data-toggle="tab" href="#kt_user_edit_tab_1">
                        <span class="nav-icon">
                            <span class="svg-icon">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                        <path
                                            d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                        <span class="nav-text font-size-lg">Profile</span>
                    </a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="nav-item">
                    <a class="nav-link {{ old('mode', $mode ?? 'profile') == 'rights' ? 'active' : '' }}"
                        data-toggle="tab" href="#kt_user_edit_tab_4">
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
                        <span class="nav-text font-size-lg">Role & Permissions</span>
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
            <div class="tab-pane {{ old('mode', $mode ?? 'profile') == 'profile' ? 'show active' : '' }}"
                id="kt_user_edit_tab_1" role="tabpanel">

                <form id="kt_form" class="form" method="POST" action="{{ route('settings.team.store') }}">
                    @csrf

                    <input type="hidden" name="id" value="{{ $user->id ?? 0 }}">
                    <input type="hidden" name="mode" value="profile">

                    <!--begin::Row-->
                    <div class="row">
                        {{-- <div class="col-xl-3"></div> --}}
                        <div class="col-md-6">

                            <!--begin::Group-->
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input
                                    class="form-control form-control-lg {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    type="text" name="name" placeholder="First Name"
                                    value="{{ old('name', $user->name ?? '') }}" />

                                @if ($errors->has('name'))
                                <span class=" invalid-feedback">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <!--end::Group-->

                        </div>
                        <div class="col-md-6">

                            <!--begin::Group-->
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input
                                    class="form-control form-control-lg {{ $errors->has('surname') ? 'is-invalid' : '' }}"
                                    type="text" name="surname" placeholder="Last Name"
                                    value="{{ old('surname', $user->surname ?? '') }}" />

                                @if ($errors->has('surname'))
                                <span class=" invalid-feedback">{{ $errors->first('surname') }}</span>
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
                                        name="email" value="{{ old('email', $user->email ?? '') }}"
                                        placeholder="Email Address" />
                                </div>

                                @if ($errors->has('email'))
                                <span class=" invalid-feedback">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <!--end::Group-->

                        </div>
                        <div class="col-md-6">

                            <!--begin::Group-->
                            <div class="form-group">
                                <label class="form-label">Contact Phone</label>
                                <div class="input-group input-group-lg {{ $errors->has('phone') ? 'is-invalid' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="la la-phone"></i>
                                        </span>
                                    </div>
                                    <input type="tel"
                                        class="form-control form-control-lg {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                        name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                        placeholder="Contact Phone" />
                                </div>

                                @if ($errors->has('phone'))
                                <span class=" invalid-feedback">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                            <!--end::Group-->

                        </div>

                        <div class="col-md-12">
                            <hr>
                        </div>

                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-md-6">

                                    <!--begin::Group-->
                                    <div class="form-group">
                                        <label class="form-label">Current Password</label>
                                        <input
                                            class="form-control form-control-lg {{ $errors->has('old_password') ? 'is-invalid' : '' }}"
                                            name="old_password" type="password" value="" />

                                        @if ($errors->has('old_password'))
                                        <span class=" invalid-feedback">{{ $errors->first('old_password') }}</span>
                                        @endif
                                    </div>
                                    <!--end::Group-->

                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">

                            <!--begin::Group-->
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input
                                    class="form-control form-control-lg {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    name="password" type="password" value="" />

                                @if ($errors->has('password'))
                                <span class=" invalid-feedback">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <!--end::Group-->

                        </div>
                        <div class="col-md-6">

                            <!--begin::Group-->
                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <input
                                    class="form-control form-control-lg {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                    name="password_confirmation" type="password" value="" />

                                @if ($errors->has('password_confirmation'))
                                <span class=" invalid-feedback">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                            <!--end::Group-->

                        </div>
                        <div class="col-md-12 my-2">

                            <!--begin::Group-->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>

                        </div>
                    </div>
                    <!--end::Row-->

                </form>

            </div>
            <!--end::Tab-->

            <!--begin::Tab-->
            <div class="tab-pane {{ old('mode', $mode ?? 'profile') == 'rights' ? 'show active' : '' }}"
                id="kt_user_edit_tab_4" role="tabpanel">

                <form id="kt_form" class="form" method="POST" action="{{ route('settings.team.store') }}">
                    @csrf

                    <input type="hidden" name="id" value="{{ $user->id ?? 0 }}">
                    <input type="hidden" name="mode" value="rights">

                    <div class="row">
                        {{-- <div class="col-xl-3"></div> --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12 row">
                                    <h6 class="text-dark font-weight-bold mb-4">Settings</h6>
                                </div>
                                <div class="col-md-12 row">
                                    <label class="form-label">General</label>
                                </div>
                                <div class="col-md-12 row">
                                    <div class="row">
                                        <div class="checkbox-inline col-md-3">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" checked="checked" />
                                                <span></span>Show</label>
                                        </div>
                                        <div class="checkbox-inline col-md-3">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" />
                                                <span></span>Add</label>
                                        </div>
                                        <div class="checkbox-inline col-md-3">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" />
                                                <span></span>Edit</label>
                                        </div>
                                        <div class="checkbox-inline col-md-3">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" checked="checked" />
                                                <span></span>Delete</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">

                            <!--begin::Group-->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                            <!--end::Group-->

                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!--begin::Card body-->
</div>

@endsection


@section('styles')

@endsection


@section('scripts')

@endsection
