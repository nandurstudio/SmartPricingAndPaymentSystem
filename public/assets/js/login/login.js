$(document).ready(function () {
    // Fungsi untuk toggle show/hide password
    function togglePassword() {
        var passwordInput = $("#password");
        var toggleIcon = $("#toggle-password i");

        if (passwordInput.attr("type") === "password") {
            // Ubah input password menjadi text
            passwordInput.attr("type", "text");
            passwordInput.css({
                'border-top-left-radius': '0',
                'border-top-right-radius': '0',
                'border-bottom-left-radius': 'var(--bs-border-radius)',
                'border-bottom-right-radius': 'var(--bs-border-radius)',
                'margin-bottom': '-1px'  // Tetapkan margin-bottom tetap -1px
            });
            toggleIcon.removeClass("bi-eye-slash").addClass("bi-eye");
        } else {
            // Ubah input text kembali menjadi password
            passwordInput.attr("type", "password");
            passwordInput.css({
                'border-top-left-radius': '0',
                'border-top-right-radius': '0',
                'border-bottom-left-radius': 'var(--bs-border-radius)',
                'border-bottom-right-radius': 'var(--bs-border-radius)',
                'margin-bottom': '-1px'  // Tetapkan margin-bottom tetap -1px
            });
            toggleIcon.removeClass("bi-eye").addClass("bi-eye-slash");
        }
    }

    // Menjalankan togglePassword saat ikon diklik
    $("#toggle-password").on("click", togglePassword);

    // Event listener for form submission
    $("form").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        var empID = $("#floatingInput").val();
        var password = $("#password").val();

        // Validate empty fields
        if (empID === "" || password === "") {
            alert("EmpID or Password cannot be empty");
            return;
        }

        // Send login data via AJAX
        $.ajax({
            url: '/login',  // Pastikan ini sesuai dengan route
            type: 'POST',
            data: {
                txtEmpID: empID,
                txtPassword: password
            },
            success: function (response) {
                window.location.href = '/';  // Redirect ke halaman sukses
                console.log("success");
            },
            error: function (xhr, status, error) {
                alert(error + "\nLogin failed, please try again.");
            }
        });
    });
});
