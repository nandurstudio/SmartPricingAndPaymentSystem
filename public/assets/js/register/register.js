$(document).ready(function () {
    $('.btn-close').on('click', function () {
        window.location.href = '/login';
    });

    // Function to check if all conditions are met
    function checkFormValidity() {
        var isValid = true;

        // Cek apakah username dan email valid
        var username = $('#username').val();
        var email = $('#email').val();
        var usernameError = $('#usernameError').is(':visible');
        var emailError = $('#emailError').is(':visible');

        // Pastikan form tidak kosong dan tidak ada error
        if (username === '' || usernameError || email === '' || emailError) {
            isValid = false;
        }

        // Enable/Disable tombol submit
        $('#submitBtn').prop('disabled', !isValid);
    }

    // Validasi form dan submit
    $('#submitBtn').on('click', function (e) {
        e.preventDefault(); // Prevent submit langsung

        // Validasi form
        var form = $(this).closest('form')[0];
        if (form.checkValidity() === false) {
            // Jika form tidak valid, tampilkan pesan dan keluar
            form.reportValidity();
            return; // Jangan lanjutkan ke proses submit
        }

        // Jika form valid, lanjutkan dengan proses submit
        $(this).prop('disabled', true);
        $('#btnText').text('Signing up...');
        $('#btnSpinner').removeClass('d-none');

        $('#googleBtn').prop('disabled', true); // Disable tombol Google juga

        // Submit form secara manual
        $(this).closest('form').submit();
    });

    // Cek username dengan aturan baru saat mengetik
    $('#username').on('input', function () {
        var username = $(this).val();

        // Validasi username dengan regex: hanya huruf kecil, angka, titik, dan underscore
        var usernameRegex = /^[a-z0-9._]+$/;

        if (username) {
            if (!usernameRegex.test(username)) {
                $('#usernameError').text('Username hanya boleh huruf kecil, angka, titik, dan underscore').show();
            } else {
                $.ajax({
                    url: '/register/checkUsername',
                    type: 'POST',
                    data: { username: username },
                    success: function (response) {
                        if (response == 'exists') {
                            $('#usernameError').text('Username sudah terdaftar').show();
                        } else {
                            $('#usernameError').hide();
                        }
                    }
                });
            }
        } else {
            $('#usernameError').hide(); // Jika username kosong, sembunyikan pesan error
        }
        // Cek validitas form setelah pengecekan username
        checkFormValidity();
    });

    // Cek email saat mengetik
    $('#email').on('input', function () {
        var email = $(this).val();
        if (email) {
            $.ajax({
                url: '/register/checkEmail',
                type: 'POST',
                data: { email: email },
                success: function (response) {
                    if (response == 'exists') {
                        $('#emailError').text('Email sudah terdaftar').show();
                    } else {
                        $('#emailError').hide();
                    }
                    // Cek validitas form setelah pengecekan email
                    checkFormValidity();
                }
            });
        }
    });

    // Cek validitas form ketika inputan berubah
    $('#username, #email').on('input', function () {
        checkFormValidity();
    });

    // Initial check on page load
    checkFormValidity();
});
