<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Register Your Account - Smart Pricing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    <?= $this->renderSection('content') ?>
    <!-- Footer -->
    <footer class="text-center mt-5 pb-4">
        <p class="text-muted">&copy; 2025 Smart Pricing System - Kelompok 5</p>
    </footer>    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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
                });
            });

            // Auto-focus on first input
            document.getElementById('fullName').focus();
        });
    </script>
</body>
</html>