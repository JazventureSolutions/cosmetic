{{-- <x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
@csrf

<!-- Email Address -->
<div>
    <x-label for="email" :value="__('Email')" />

    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
</div>

<!-- Password -->
<div class="mt-4">
    <x-label for="password" :value="__('Password')" />

    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
        autocomplete="current-password" />
</div>

<!-- Remember Me -->
<div class="block mt-4">
    <label for="remember_me" class="inline-flex items-center">
        <input id="remember_me" type="checkbox"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            name="remember">
        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
    </label>
</div>

<div class="flex items-center justify-end mt-4">
    @if (Route::has('password.request'))
    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
        {{ __('Forgot your password?') }}
    </a>
    @endif

    <x-button class="ml-3">
        {{ __('Log in') }}
    </x-button>
</div>
</form>
</x-auth-card>
</x-guest-layout> --}}

<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 11 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <title>Login | Circumcision Clinic</title>
    <meta name="description" content="Circumcision Clinic" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{ url('/') }}/assets/css/pages/login/classic/login-1.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ url('/') }}/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{{ url('/') }}/assets/media/logos/favicon.ico" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body"
    class="header-mobile-fixed subheader-enabled aside-enabled aside-fixed aside-secondary-enabled page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white"
            id="kt_login">
            <!--begin::Aside-->
            <div class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-10"
                style="background-image: url({{ url('/') }}/assets/media/bg/bg-4.jpg);">
                <!--begin: Aside Container-->
                <div class="d-flex flex-row-fluid flex-column justify-content-between">
                    <!--begin: Aside header-->
                    <a href="#" class="flex-column-auto mt-5 pb-lg-0 pb-10">
                        <img src="{{ url('/') }}/logo.jpg" class="max-h-70px" alt="" />
                    </a>
                    <!--end: Aside header-->
                    <!--begin: Aside content-->
                    <div class="flex-column-fluid d-flex flex-column justify-content-center">
                        <h3 class="font-size-h1 mb-5 text-white">Welcome to Circumcision Clinic!</h3>
                        {{-- <p class="font-weight-lighter text-white opacity-80">The ultimate Bootstrap, Angular 8, React
                            &amp; VueJS admin theme framework for next generation web apps.</p> --}}
                    </div>
                    <!--end: Aside content-->
                    <!--begin: Aside footer for desktop-->
                    <div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
                        <div class="opacity-70 font-weight-bold text-white">© {{ \Carbon\Carbon::now()->year }} Jazventure Solutions</div>
                    </div>
                    <!--end: Aside footer for desktop-->
                </div>
                <!--end: Aside Container-->
            </div>
            <!--begin::Aside-->
            <!--begin::Content-->
            <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
                <!--begin::Content body-->
                <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
                    <!--begin::Signin-->
                    <div class="login-form login-signin">
                        <div class="text-center mb-10 mb-lg-20">
                            <h3 class="font-size-h1">Sign In</h3>
                            <p class="text-muted font-weight-bold">Enter your email and password</p>
                        </div>
                        <!--begin::Form-->
                        <form class="form" novalidate="novalidate" id="kt_login_signin_form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <input class="form-control form-control-solid h-auto py-5 px-6" type="email"
                                    placeholder="Email" name="email" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-solid h-auto py-5 px-6" type="password"
                                    placeholder="Password" name="password" autocomplete="off" />
                            </div>
                            <!--begin::Action-->
                            <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                <a href="javascript:;" class="text-dark-50 text-hover-primary my-3 mr-2"
                                    id="kt_login_forgot">
                                    {{-- Forgot Password ? --}}
                                </a>
                                <button type="submit" id="kt_login_signin_submit"
                                    class="btn btn-primary font-weight-bold px-9 py-4 my-3">Sign In</button>
                            </div>
                            <!--end::Action-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Signin-->
                    <!--begin::Signup-->
                    <div class="login-form login-signup">
                        <div class="text-center mb-10 mb-lg-20">
                            <h3 class="font-size-h1">Sign Up</h3>
                            <p class="text-muted font-weight-bold">Enter your details to create your account</p>
                        </div>
                        <!--begin::Form-->
                        <form class="form" novalidate="novalidate" id="kt_login_signup_form">
                            <div class="form-group">
                                <input class="form-control form-control-solid h-auto py-5 px-6" type="text"
                                    placeholder="Fullname" name="fullname" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-solid h-auto py-5 px-6" type="email"
                                    placeholder="Email" name="email" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-solid h-auto py-5 px-6" type="password"
                                    placeholder="Password" name="password" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-solid h-auto py-5 px-6" type="password"
                                    placeholder="Confirm password" name="cpassword" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label class="checkbox mb-0">
                                    <input type="checkbox" name="agree" />
                                    <span></span>I Agree the
                                    <a href="#">terms and conditions</a></label>
                            </div>
                            <div class="form-group d-flex flex-wrap flex-center">
                                <button type="button" id="kt_login_signup_submit"
                                    class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Submit</button>
                                <button type="button" id="kt_login_signup_cancel"
                                    class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-4">Cancel</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Signup-->
                    <!--begin::Forgot-->
                    <div class="login-form login-forgot">
                        <div class="text-center mb-10 mb-lg-20">
                            <h3 class="font-size-h1">Forgotten Password ?</h3>
                            <p class="text-muted font-weight-bold">Enter your email to reset your password</p>
                        </div>
                        <!--begin::Form-->
                        <form class="form" novalidate="novalidate" id="kt_login_forgot_form">
                            <div class="form-group">
                                <input class="form-control form-control-solid h-auto py-5 px-6" type="email"
                                    placeholder="Email" name="email" autocomplete="off" />
                            </div>
                            <div class="form-group d-flex flex-wrap flex-center">
                                <button type="button" id="kt_login_forgot_submit"
                                    class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Submit</button>
                                <button type="button" id="kt_login_forgot_cancel"
                                    class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-4">Cancel</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Forgot-->
                </div>
                <!--end::Content body-->
                <!--begin::Content footer for mobile-->
                <div
                    class="d-flex d-lg-none flex-column-auto flex-column flex-sm-row justify-content-between align-items-center mt-5 p-5">
                    <div class="text-dark-50 font-weight-bold order-2 order-sm-1 my-2">© 2021 Metronic</div>
                    <div class="d-flex order-1 order-sm-2 my-2">
                        <a href="#" class="text-dark-75 text-hover-primary">Privacy</a>
                        <a href="#" class="text-dark-75 text-hover-primary ml-4">Legal</a>
                        <a href="#" class="text-dark-75 text-hover-primary ml-4">Contact</a>
                    </div>
                </div>

                <!--end::Content footer for mobile-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Login-->
    </div>
    <!--end::Main-->
    <script>
        var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
    </script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#1BC5BD", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#6993FF", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#1BC5BD", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#E1E9FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };
        var LOGIN_URL = '{{ route("login") }}';
    </script>
    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="{{ url('/') }}/assets/plugins/global/plugins.bundle.js"></script>
    <script src="{{ url('/') }}/assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="{{ url('/') }}/assets/js/scripts.bundle.js"></script>
    <script src="{{ url('/') }}/assets/js/pages/my-script.js"></script>
    <!--end::Global Theme Bundle-->
    <!--begin::Page Scripts(used by this page)-->
    {{-- <script src="{{ url('/') }}/assets/js/pages/auth.js"></script> --}}
    <!--end::Page Scripts-->

    <script>
        // Class Definition
        // var KTLogin = function () {
        //     var _login;

        //     var _showForm = function (form) {
        //         var cls = 'login-' + form + '-on';
        //         var form = 'kt_login_' + form + '_form';

        //         _login.removeClass('login-forgot-on');
        //         _login.removeClass('login-signin-on');
        //         // _login.removeClass('login-signup-on');

        //         _login.addClass(cls);

        //         KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
        //     }

        //     var _handleSignInForm = function () {
        //         var validation;

        //         // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        //         validation = FormValidation.formValidation(
        //             KTUtil.getById('kt_login_signin_form'),
        //             {
        //                 fields: {
        //                     email: {
        //                         validators: {
        //                             notEmpty: {
        //                                 message: 'Email is required'
        //                             }
        //                         }
        //                     },
        //                     password: {
        //                         validators: {
        //                             notEmpty: {
        //                                 message: 'Password is required'
        //                             }
        //                         }
        //                     }
        //                 },
        //                 plugins: {
        //                     trigger: new FormValidation.plugins.Trigger(),
        //                     submitButton: new FormValidation.plugins.SubmitButton(),
        //                     //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
        //                     bootstrap: new FormValidation.plugins.Bootstrap()
        //                 }
        //             }
        //         );

        //         $('#kt_login_signin_submit').on('click', function (e) {
        //             e.preventDefault();

        //             validation.validate().then(function (status) {
        //                 if (status == 'Valid') {

        //                     blockPage();

        //                     $.ajax({
        //                         type: 'POST',
        //                         url: LOGIN_URL,
        //                         data: $('#kt_login_signin_form').serialize(),
        //                         success: function (data) {
        //                             unblockPage();

        //                             showSuccessAlert("Enjoy!", () => {
        //                                 KTUtil.scrollTop();
        //                                 // window.location.reload();
        //                             });

        //                             window.location.reload();
        //                         },
        //                         error: function (error) {
        //                             unblockPage()

        //                             showErrorAlert(error.responseJSON.message, () => {
        //                                 KTUtil.scrollTop();
        //                             });
        //                         }
        //                     });
        //                 } else {
        //                     showErrorAlert("Sorry, looks like there are some errors detected, please try again.", () => {
        //                         KTUtil.scrollTop();
        //                     });
        //                 }
        //             });
        //         });

        //         // Handle forgot button
        //         $('#kt_login_forgot').on('click', function (e) {
        //             e.preventDefault();
        //             _showForm('forgot');
        //         });

        //         // // Handle signup
        //         // $('#kt_login_signup').on('click', function (e) {
        //         //     e.preventDefault();
        //         //     _showForm('signup');
        //         // });
        //     }

        //     // var _handleSignUpForm = function (e) {
        //     //     var validation;
        //     //     var form = KTUtil.getById('kt_login_signup_form');

        //     //     // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        //     //     validation = FormValidation.formValidation(
        //     //         form,
        //     //         {
        //     //             fields: {
        //     //                 fullname: {
        //     //                     validators: {
        //     //                         notEmpty: {
        //     //                             message: 'Username is required'
        //     //                         }
        //     //                     }
        //     //                 },
        //     //                 email: {
        //     //                     validators: {
        //     //                         notEmpty: {
        //     //                             message: 'Email address is required'
        //     //                         },
        //     //                         emailAddress: {
        //     //                             message: 'The value is not a valid email address'
        //     //                         }
        //     //                     }
        //     //                 },
        //     //                 password: {
        //     //                     validators: {
        //     //                         notEmpty: {
        //     //                             message: 'The password is required'
        //     //                         }
        //     //                     }
        //     //                 },
        //     //                 cpassword: {
        //     //                     validators: {
        //     //                         notEmpty: {
        //     //                             message: 'The password confirmation is required'
        //     //                         },
        //     //                         identical: {
        //     //                             compare: function () {
        //     //                                 return form.querySelector('[name="password"]').value;
        //     //                             },
        //     //                             message: 'The password and its confirm are not the same'
        //     //                         }
        //     //                     }
        //     //                 },
        //     //                 agree: {
        //     //                     validators: {
        //     //                         notEmpty: {
        //     //                             message: 'You must accept the terms and conditions'
        //     //                         }
        //     //                     }
        //     //                 },
        //     //             },
        //     //             plugins: {
        //     //                 trigger: new FormValidation.plugins.Trigger(),
        //     //                 bootstrap: new FormValidation.plugins.Bootstrap()
        //     //             }
        //     //         }
        //     //     );

        //     //     $('#kt_login_signup_submit').on('click', function (e) {
        //     //         e.preventDefault();

        //     //         validation.validate().then(function (status) {
        //     //             if (status == 'Valid') {
        //     //                 swal.fire({
        //     //                     text: "All is cool! Now you submit this form",
        //     //                     icon: "success",
        //     //                     buttonsStyling: false,
        //     //                     confirmButtonText: "Ok, got it!",
        //     //                     customClass: {
        //     //                         confirmButton: "btn font-weight-bold btn-light-primary"
        //     //                     }
        //     //                 }).then(function () {
        //     //                     KTUtil.scrollTop();
        //     //                 });
        //     //             } else {
        //     //                 swal.fire({
        //     //                     text: "Sorry, looks like there are some errors detected, please try again.",
        //     //                     icon: "error",
        //     //                     buttonsStyling: false,
        //     //                     confirmButtonText: "Ok, got it!",
        //     //                     customClass: {
        //     //                         confirmButton: "btn font-weight-bold btn-light-primary"
        //     //                     }
        //     //                 }).then(function () {
        //     //                     KTUtil.scrollTop();
        //     //                 });
        //     //             }
        //     //         });
        //     //     });

        //     //     // Handle cancel button
        //     //     $('#kt_login_signup_cancel').on('click', function (e) {
        //     //         e.preventDefault();

        //     //         _showForm('signin');
        //     //     });
        //     // }

        //     var _handleForgotForm = function (e) {
        //         var validation;

        //         // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        //         validation = FormValidation.formValidation(
        //             KTUtil.getById('kt_login_forgot_form'),
        //             {
        //                 fields: {
        //                     email: {
        //                         validators: {
        //                             notEmpty: {
        //                                 message: 'Email address is required'
        //                             },
        //                             emailAddress: {
        //                                 message: 'The value is not a valid email address'
        //                             }
        //                         }
        //                     }
        //                 },
        //                 plugins: {
        //                     trigger: new FormValidation.plugins.Trigger(),
        //                     bootstrap: new FormValidation.plugins.Bootstrap()
        //                 }
        //             }
        //         );

        //         // Handle submit button
        //         $('#kt_login_forgot_submit').on('click', function (e) {
        //             e.preventDefault();

        //             validation.validate().then(function (status) {
        //                 if (status == 'Valid') {
        //                     // Submit form
        //                     KTUtil.scrollTop();
        //                 } else {
        //                     swal.fire({
        //                         text: "Sorry, looks like there are some errors detected, please try again.",
        //                         icon: "error",
        //                         buttonsStyling: false,
        //                         confirmButtonText: "Ok, got it!",
        //                         customClass: {
        //                             confirmButton: "btn font-weight-bold btn-light-primary"
        //                         }
        //                     }).then(function () {
        //                         KTUtil.scrollTop();
        //                     });
        //                 }
        //             });
        //         });

        //         // Handle cancel button
        //         $('#kt_login_forgot_cancel').on('click', function (e) {
        //             e.preventDefault();

        //             _showForm('signin');
        //         });
        //     }

        //     // Public Functions
        //     return {
        //         // public functions
        //         init: function () {
        //             _login = $('#kt_login');

        //             _handleSignInForm();
        //             // _handleSignUpForm();
        //             _handleForgotForm();
        //         }
        //     };
        // }();

        // // Class Initialization
        // jQuery(document).ready(function () {
        //     KTLogin.init();
        // });
    </script>
</body>
<!--end::Body-->

</html>
