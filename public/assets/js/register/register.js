$(document).ready(function () {
    $('.btn-close').on('click', function () {
        window.location.href = '/login';
    });
    $('#submitBtn').on('click', function (e) {
        e.preventDefault(); // prevent submit langsung
        $(this).prop('disabled', true);
        $('#btnText').text('Signing up...');
        $('#btnSpinner').removeClass('d-none');

        $('#googleBtn').prop('disabled', true); // disable tombol Google juga
        $(this).closest('form').submit(); // submit manual
    });
});
