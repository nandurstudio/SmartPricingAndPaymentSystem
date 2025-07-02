<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Register Your Account - Smart Pricing System</title>
    
    <!-- Bootstrap Icons - Load first to avoid overrides -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css') ?>" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css') ?>" />
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/themes/default.css') ?>" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>" />
    
    <?= $this->renderSection('styles') ?>
    <style>
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 3;
        }

        .password-toggle:hover {
            color: #0d6efd;
        }

        .form-floating {
            position: relative;
        }

        .form-floating input[type="password"],
        .form-floating input[type="text"] {
            padding-right: 45px;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 0.8rem;
        }

        .strength-weak {
            color: #dc3545;
        }

        .strength-medium {
            color: #ffc107;
        }

        .strength-strong {
            color: #198754;
        }

        .match-indicator {
            margin-top: 5px;
            font-size: 0.8rem;
        }

        .text-success {
            color: #198754 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class="fw-bold mb-0">Register Your Account</h3>
                    </div>
                    <div class="card-body p-4">
                        <form id="registrationForm" action="<?= base_url('register/createUser') ?>" method="post" autocomplete="off">
                            <?= csrf_field() ?>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="fullName" name="txtFullName" type="text" placeholder="Full Name" required />
                                <label for="fullName"><i class="bi bi-person me-2"></i>Full Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="userName" name="txtUserName" type="text" placeholder="Username" required />
                                <label for="userName"><i class="bi bi-person-badge me-2"></i>Username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="txtEmail" type="email" placeholder="name@example.com" required />
                                <label for="email"><i class="bi bi-envelope me-2"></i>Email address</label>
                            </div>
                            <div class="form-floating mb-3 position-relative">
                                <input class="form-control" id="password" name="txtPassword" type="password" placeholder="Password" required />
                                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                                <span class="password-toggle bi bi-eye" data-target="password" style="top: 50%; right: 15px; position: absolute;"></span>
                                <div id="passwordStrength" class="password-strength"></div>
                            </div>
                            <div class="form-floating mb-3 position-relative">
                                <input class="form-control" id="passwordConfirm" name="password_confirm" type="password" placeholder="Confirm Password" required />
                                <label for="passwordConfirm"><i class="bi bi-lock me-2"></i>Confirm Password</label>
                                <span class="password-toggle bi bi-eye" data-target="passwordConfirm" style="top: 50%; right: 15px; position: absolute;"></span>
                                <div id="passwordMatch" class="match-indicator"></div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="terms" name="terms_agreement" type="checkbox" value="1" required />
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="<?= base_url('terms') ?>">Terms of Service</a> and <a href="<?= base_url('privacy-policy') ?>">Privacy Policy</a>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <span id="btnText"><i class="bi bi-person-plus me-2"></i>Create Account</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="<?= base_url('login') ?>" class="small text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Already have an account? Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="text-center mt-5 pb-4">
        <p class="text-muted">&copy; 2025 Smart Pricing System - Kelompok 5</p>
    </footer>
    
    <!-- Scripts -->
    <!-- jQuery First -->
    <script src="<?= base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
    
    <!-- Bootstrap Bundle -->
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>

    <?= $this->renderSection('scripts') ?>
    <script>
        // Password visibility toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordToggles = document.querySelectorAll('.password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetInput = document.getElementById(targetId);
                    if (!targetInput) return;
                    const isPassword = targetInput.type === 'password';
                    targetInput.type = isPassword ? 'text' : 'password';
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            });

            // Password strength checker
            const passwordInput = document.getElementById('password');
            const passwordStrength = document.getElementById('passwordStrength');
            if (passwordInput && passwordStrength) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    const strength = getPasswordStrength(password);
                    passwordStrength.innerHTML = `
                        <div class="strength-${strength.class}">
                            <i class="bi bi-shield-lock me-1"></i>
                            Password strength: ${strength.text}
                        </div>
                    `;
                    // Check password match when typing in password field
                    checkPasswordMatch();
                });
            }

            // Password confirmation checker
            const passwordConfirmInput = document.getElementById('passwordConfirm');
            const passwordMatch = document.getElementById('passwordMatch');
            if (passwordConfirmInput && passwordInput && passwordMatch) {
                passwordConfirmInput.addEventListener('input', checkPasswordMatch);
            }

            function checkPasswordMatch() {
                if (!passwordInput || !passwordConfirmInput || !passwordMatch) return;
                const password = passwordInput.value;
                const confirmPassword = passwordConfirmInput.value;
                if (confirmPassword.length === 0) {
                    passwordMatch.innerHTML = '';
                    return;
                }
                if (password === confirmPassword) {
                    passwordMatch.innerHTML = `
                        <div class="text-success">
                            <i class="bi bi-check-circle me-1"></i>
                            Passwords match
                        </div>
                    `;
                } else {
                    passwordMatch.innerHTML = `
                        <div class="text-danger">
                            <i class="bi bi-x-circle me-1"></i>
                            Passwords do not match
                        </div>
                    `;
                }
            }

            function getPasswordStrength(password) {
                let score = 0;
                // Length check
                if (password.length >= 8) score++;
                if (password.length >= 12) score++;
                // Character variety checks
                if (/[a-z]/.test(password)) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[^A-Za-z0-9]/.test(password)) score++;
                if (score < 3) {
                    return {
                        class: 'weak',
                        text: 'Weak'
                    };
                } else if (score < 5) {
                    return {
                        class: 'medium',
                        text: 'Medium'
                    };
                } else {
                    return {
                        class: 'strong',
                        text: 'Strong'
                    };
                }
            }
            // Form submission with loading state
            const form = document.getElementById('registrationForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            if (form && submitBtn && btnText) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Always prevent default form submission
                    // Prevent submission if passwords don't match
                    const password = passwordInput ? passwordInput.value : '';
                    const confirmPassword = passwordConfirmInput ? passwordConfirmInput.value : '';
                    if (password !== confirmPassword) {
                        alert('Passwords do not match. Please check and try again.');
                        return;
                    }
                    // Show loading state
                    submitBtn.disabled = true;
                    btnText.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Creating Account...';
                    // Don't use separate spinner since we already have one in the text
                    // Submit form via AJAX
                    const formData = new FormData(form);
                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                                alertDiv.innerHTML = `
                                <i class="bi bi-check-circle me-2"></i>
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                                form.parentNode.insertBefore(alertDiv, form);
                                // Redirect after 2 seconds
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 2000);
                            } else {
                                // Handle validation errors
                                if (data.errors) {
                                    // Clear previous errors
                                    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                                    // Display new errors
                                    Object.keys(data.errors).forEach(field => {
                                        const input = document.querySelector(`[name="${field}"]`);
                                        if (input) {
                                            input.classList.add('is-invalid');
                                            const errorDiv = document.createElement('div');
                                            errorDiv.className = 'invalid-feedback d-block';
                                            errorDiv.textContent = data.errors[field];
                                            input.parentNode.appendChild(errorDiv);
                                        }
                                    });
                                } else {
                                    // Show general error message
                                    const alertDiv = document.createElement('div');
                                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                                    alertDiv.innerHTML = `
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    ${data.message || 'Registration failed. Please try again.'}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                `;
                                    form.parentNode.insertBefore(alertDiv, form);
                                }
                                // Reset button state
                                submitBtn.disabled = false;
                                btnText.innerHTML = '<i class="bi bi-person-plus me-2"></i>Create Account';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Show error message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                            alertDiv.innerHTML = `
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            An error occurred. Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                            form.parentNode.insertBefore(alertDiv, form);
                            // Reset button state
                            submitBtn.disabled = false;
                            btnText.innerHTML = '<i class="bi bi-person-plus me-2"></i>Create Account';
                        });
                });
            }
            // Auto-focus on first input
            const fullNameInput = document.getElementById('fullName');
            if (fullNameInput) {
                fullNameInput.focus();
            }
        });
    </script>
</body>

</html>