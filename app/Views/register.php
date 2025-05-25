<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Register Your Account - Smart Pricing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm mt-5">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Create Your Account</h4>
                        <p class="mb-0 small">Join Smart Pricing System today</p>
                    </div>
                    <div class="card-body p-4">
                        <!-- Success Message -->
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= session()->getFlashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Error Message -->
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= session()->getFlashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Registration Form -->
                        <form method="post" action="/register/createUser" id="registrationForm">
                            <?= csrf_field() ?>
                            
                            <!-- Full Name -->
                            <div class="form-floating mb-3">
                                <input type="text" name="txtFullName" class="form-control" id="fullName" 
                                       placeholder="Full Name" required minlength="3" maxlength="100" 
                                       value="<?= old('txtFullName') ?>">
                                <label for="fullName"><i class="fas fa-user me-2"></i>Full Name</label>
                                <?php if (isset($validation) && $validation->hasError('txtFullName')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('txtFullName'); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Username -->
                            <div class="form-floating mb-3">
                                <input type="text" name="txtUserName" class="form-control" id="username" 
                                       placeholder="Username" required minlength="4" maxlength="50" 
                                       value="<?= old('txtUserName') ?>">
                                <label for="username"><i class="fas fa-at me-2"></i>Username</label>
                                <div class="form-text">Username must be 4-50 characters long</div>
                                <?php if (isset($validation) && $validation->hasError('txtUserName')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('txtUserName'); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input type="email" name="txtEmail" class="form-control" id="email" 
                                       placeholder="Email" required maxlength="100" 
                                       value="<?= old('txtEmail') ?>">
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                                <?php if (isset($validation) && $validation->hasError('txtEmail')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('txtEmail'); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input type="password" name="txtPassword" class="form-control" id="password" 
                                       placeholder="Password" required minlength="6" maxlength="255">
                                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                <i class="fas fa-eye password-toggle" id="togglePassword" data-target="password"></i>
                                <div class="password-strength" id="passwordStrength"></div>
                                <?php if (isset($validation) && $validation->hasError('txtPassword')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('txtPassword'); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Repeat Password -->
                            <div class="form-floating mb-3">
                                <input type="password" name="password_confirm" class="form-control" id="passwordConfirm" 
                                       placeholder="Repeat Password" required minlength="6" maxlength="255">
                                <label for="passwordConfirm"><i class="fas fa-lock me-2"></i>Repeat Password</label>
                                <i class="fas fa-eye password-toggle" id="togglePasswordConfirm" data-target="passwordConfirm"></i>
                                <div class="match-indicator" id="passwordMatch"></div>
                                <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('password_confirm'); ?></div>
                                <?php endif; ?>
                            </div>                            <!-- Terms and Conditions Agreement -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="terms_agreement" value="1" 
                                       id="termsAgreement" required>
                                <label class="form-check-label" for="termsAgreement">
                                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> 
                                    and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                </label>
                                <?php if (isset($validation) && $validation->hasError('terms_agreement')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('terms_agreement'); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="txtPhoto" value="default.png">
                            <input type="hidden" name="intRoleID" value="5">
                            <input type="hidden" name="bitActive" value="1"><!-- Submit Button -->
                            <button class="btn btn-primary btn-lg w-100 mb-3" type="submit" id="submitBtn">
                                <span id="btnText">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                            </button>

                            <!-- Alternative Login Options -->
                            <div class="text-center">
                                <div class="border-top pt-3 mb-3">
                                    <small class="text-muted">Or sign up with</small>
                                </div>
                                <button class="btn btn-outline-danger w-100" type="button" id="googleBtn" 
                                        onclick="window.location='<?php echo base_url('/auth/googleLogin'); ?>'">
                                    <i class="fab fa-google me-2"></i>Continue with Google
                                </button>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i> By signing up with Google, we'll use your name and email automatically
                                    </small>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-light">
                        <p class="mb-0">
                            Already have an account? 
                            <a href="/login" class="text-decoration-none fw-bold">Sign in here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5 pb-4">
        <p class="text-muted">&copy; 2025 Smart Pricing System - Kelompok 5</p>
    </footer>    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordToggles = document.querySelectorAll('.password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetInput = document.getElementById(targetId);
                    const isPassword = targetInput.type === 'password';
                    
                    targetInput.type = isPassword ? 'text' : 'password';
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            });

            // Password strength checker
            const passwordInput = document.getElementById('password');
            const passwordStrength = document.getElementById('passwordStrength');
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = getPasswordStrength(password);
                
                passwordStrength.innerHTML = `
                    <div class="strength-${strength.class}">
                        <i class="fas fa-shield-alt me-1"></i>
                        Password strength: ${strength.text}
                    </div>
                `;
                
                // Check password match when typing in password field
                checkPasswordMatch();
            });

            // Password confirmation checker
            const passwordConfirmInput = document.getElementById('passwordConfirm');
            const passwordMatch = document.getElementById('passwordMatch');
            
            passwordConfirmInput.addEventListener('input', checkPasswordMatch);

            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = passwordConfirmInput.value;
                
                if (confirmPassword.length === 0) {
                    passwordMatch.innerHTML = '';
                    return;
                }
                
                if (password === confirmPassword) {
                    passwordMatch.innerHTML = `
                        <div class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Passwords match
                        </div>
                    `;
                } else {
                    passwordMatch.innerHTML = `
                        <div class="text-danger">
                            <i class="fas fa-times-circle me-1"></i>
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
                    return { class: 'weak', text: 'Weak' };
                } else if (score < 5) {
                    return { class: 'medium', text: 'Medium' };
                } else {
                    return { class: 'strong', text: 'Strong' };
                }
            }            // Form submission with loading state
            const form = document.getElementById('registrationForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Always prevent default form submission
                
                // Prevent submission if passwords don't match
                const password = passwordInput.value;
                const confirmPassword = passwordConfirmInput.value;
                
                if (password !== confirmPassword) {
                    alert('Passwords do not match. Please check and try again.');
                    return;
                }

                // Show loading state
                submitBtn.disabled = true;
                btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
                btnSpinner.classList.remove('d-none');

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
                            <i class="fas fa-check-circle me-2"></i>
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
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                ${data.message || 'Registration failed. Please try again.'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                            form.parentNode.insertBefore(alertDiv, form);
                        }
                        
                        // Reset button state
                        submitBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-user-plus me-2"></i>Create Account';
                        btnSpinner.classList.add('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Show error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        An error occurred. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    form.parentNode.insertBefore(alertDiv, form);
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    btnText.innerHTML = '<i class="fas fa-user-plus me-2"></i>Create Account';
                    btnSpinner.classList.add('d-none');
                });
            });

            // Auto-focus on first input
            document.getElementById('fullName').focus();
        });
    </script>
</body>
</html>