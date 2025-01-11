"use strict";

function blockPage() {
    KTApp.blockPage({
        overlayColor: '#000000',
        state: 'primary',
        message: 'Processing...'
    });
}

function unblockPage() {
    KTApp.unblockPage();
}

function showSuccessAlert(message = null, callback) {
    console.log('success', message);
    swal.fire({
        text: message ?? 'Message Here!',
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Lets get in!",
        customClass: {
            confirmButton: "btn font-weight-bold btn-light-primary"
        }
    }).then(function () {
        callback();
    });
}

function showErrorAlert(message = null, callback) {
    console.log('error', message);
    swal.fire({
        text: message ?? "Sorry, looks like there are some errors detected, please try again.",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        customClass: {
            confirmButton: "btn font-weight-bold btn-light-primary"
        }
    }).then(function () {
        callback();
    });
}

jQuery(document).ready(function () {

    $('.basic-select2').select2();

    $(document).on('click', '[data-form-submit]', function (e) {
        e.preventDefault();

        $($(this).data('form-submit'))[0].submit();
    });

    $(document).on('click', '[data-form-reset]', function (e) {
        e.preventDefault();

        $($(this).data('form-reset'))[0].reset();
    });

});
