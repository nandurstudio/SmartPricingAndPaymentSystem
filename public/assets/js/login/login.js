$(document).ready(function () {
    // Fungsi untuk toggle show/hide password
    function togglePassword() {
        var passwordInput = $("#txtPassword");
        var toggleIcon = $("#toggle-password i");

        if (passwordInput.attr("type") === "password") {
            passwordInput.attr("type", "text");
            passwordInput.css({
                'border-top-left-radius': '0',
                'border-top-right-radius': '0',
                'border-bottom-left-radius': 'var(--bs-border-radius)',
                'border-bottom-right-radius': 'var(--bs-border-radius)',
                'margin-bottom': '-1px'
            });
            toggleIcon.removeClass("bi-eye-slash").addClass("bi-eye");
        } else {
            passwordInput.attr("type", "password");
            passwordInput.css({
                'border-top-left-radius': '0',
                'border-top-right-radius': '0',
                'border-bottom-left-radius': 'var(--bs-border-radius)',
                'border-bottom-right-radius': 'var(--bs-border-radius)',
                'margin-bottom': '-1px'
            });
            toggleIcon.removeClass("bi-eye").addClass("bi-eye-slash");
        }
    }

    // Menjalankan togglePassword saat ikon diklik
    $("#toggle-password").on("click", togglePassword);

    // Event listener for form submission
    $("form").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        var email = $("#txtEmail").val().trim();
        var password = $("#txtPassword").val().trim();
        var rememberMe = $("#remember_me").is(":checked"); // Cek apakah checkbox remember me dicentang

        // Validate empty fields
        if (email === "" || password === "") {
            alert("Email or Password cannot be empty");
            return;
        }

        // Send login data via AJAX
        $.ajax({
            url: '/login',  // Pastikan ini sesuai dengan route controller
            type: 'POST',
            data: {
                txtEmail: email,
                txtPassword: password,
                remember_me: rememberMe ? 1 : 0  // Kirimkan 1 jika dicentang, 0 jika tidak
            },
            success: function (response) {
                // Redirect to home page on success
                window.location.href = '/';
                console.log("Login success");
            },
            error: function (xhr, status, error) {
                // Cek status code untuk error spesifik
                var errorMessage = "Login failed, please try again.";

                if (xhr.status === 401) {
                    errorMessage = "Unauthorized: Incorrect email or password.";
                } else if (xhr.status === 500) {
                    errorMessage = "Server error: Please try again later.";
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                alert(errorMessage);
                console.error("Login failed:", error);
            }
        });
    });
});
