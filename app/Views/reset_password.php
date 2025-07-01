<?= $this->include('layouts/head') ?>

<body class="bg-light">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-8">
                            <!-- Modern reset password form -->
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header bg-primary text-center py-4">
                                    <h3 class="fw-bold text-white my-2"><i class="bi bi-shield-lock me-2 text-white"></i>Reset Password</h3>
                                </div>
                                <div class="card-body p-4">
                                    <div class="text-center mb-4">
                                        <div class="avatar-icon mb-3">
                                            <i class="bi bi-lock-fill text-primary" style="font-size: 3rem;"></i>
                                        </div>
                                        <div class="mb-3 text-muted">Please create a new secure password for your account.</div>
                                        <?php if (isset($username) || isset($email)): ?>
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Resetting password for:
                                                <strong><?= isset($username) ? esc($username) : esc($email) ?></strong>
                                            </div>
                                        <?php endif; ?>
                                    </div> <!-- Menampilkan pesan sukses atau error dengan helper -->
                                    <?= display_flash_messages() ?>

                                    <!-- Debug Flash Data (hidden) -->
                                    <div id="debug-flash-messages" style="display: none;">
                                        <p>Session flashdata:
                                            <?php
                                            $flashData = session()->getFlashdata();
                                            echo !empty($flashData) ? print_r($flashData, true) : 'No flash data';
                                            ?>
                                        </p>
                                    </div>

                                    <!-- Form hanya ditampilkan jika tidak ada pesan sukses -->
                                    <?php if (!session()->getFlashdata('success')) : ?>
                                        <form action="<?= base_url('auth/updatePassword') ?>" method="post" id="reset-password-form">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="token" value="<?= esc($token) ?>">
                                            <!-- New Password -->
                                            <div class="form-floating mb-3 position-relative">
                                                <input class="form-control" id="txtPassword" type="password" name="txtPassword" placeholder="Enter new password" minlength="6" required />
                                                <label for="txtPassword"><i class="bi bi-lock-fill me-2"></i>New Password</label>
                                                <span class="password-toggle" onclick="togglePasswordVisibility('txtPassword')">
                                                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                                                </span>
                                                <div id="passwordStrength" class="mt-2 small"></div>
                                            </div>

                                            <!-- Confirm Password -->
                                            <div class="form-floating mb-3 position-relative">
                                                <input class="form-control" id="confirmPassword" type="password" name="confirmPassword" placeholder="Confirm new password" minlength="6" required />
                                                <label for="confirmPassword"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
                                                <span class="password-toggle" onclick="togglePasswordVisibility('confirmPassword')">
                                                    <i class="bi bi-eye-slash" id="toggleConfirmPassword"></i>
                                                </span>
                                                <div id="passwordMatch" class="mt-2 small"></div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small text-decoration-none" href="<?= base_url('/login') ?>"><i class="bi bi-arrow-left me-1"></i>Return to login</a>
                                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                                    <span id="submit-text"><i class="bi bi-check-circle me-2"></i>Update Password</span>
                                                    <span id="loading-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <div class="text-center mt-4">
                                            <a href="<?= base_url('/login') ?>" class="btn btn-primary"><i class="bi bi-arrow-left me-2"></i>Return to Login</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer text-center py-3 bg-light">
                                    <div class="small">
                                        <a href="<?= base_url('/register') ?>" class="text-decoration-none">
                                            <i class="bi bi-person-plus me-1"></i>Need an account? Sign up!
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <?= $this->include('layouts/footer') ?>
        </div>
    </div>
    <?= $this->include('layouts/scripts') ?>
    <style>
        .form-floating {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 18px;
            cursor: pointer;
            z-index: 5;
            color: #6c757d;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
        }

        .password-toggle i {
            font-size: 1.1rem;
            line-height: 1;
        }

        .password-toggle:hover {
            color: #0d6efd;
        }

        .form-floating>.form-control {
            padding-right: 45px;
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
    </style>
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(inputId === 'txtPassword' ? 'togglePassword' : 'toggleConfirmPassword');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }

        // Password strength checker
        document.getElementById('txtPassword')?.addEventListener('input', function() {
            const password = this.value;
            let strength = '';
            let strengthClass = '';

            if (password.length < 6) {
                strength = 'Weak';
                strengthClass = 'strength-weak';
            } else if (password.length < 10) {
                strength = 'Medium';
                strengthClass = 'strength-medium';
            } else {
                strength = 'Strong';
                strengthClass = 'strength-strong';
            }

            document.getElementById('passwordStrength').innerHTML =
                `<span class="${strengthClass}>
                    <i class="bi bi-shield${strength === 'Strong' ? '-check' : ''}"></i> 
                    Password strength: ${strength}
                </span>`;

            checkPasswordMatch();
        });

        // Password match checker
        document.getElementById('confirmPassword')?.addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = document.getElementById('txtPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const passwordMatch = document.getElementById('passwordMatch');

            if (!confirmPassword) {
                passwordMatch.innerHTML = '';
                return;
            }

            if (password === confirmPassword) {
                passwordMatch.innerHTML =
                    '<span class="text-success"><i class="bi bi-check-circle"></i> Passwords match</span>';
            } else {
                passwordMatch.innerHTML =
                    '<span class="text-danger"><i class="bi bi-x-circle"></i> Passwords do not match</span>';
            }
        }

        // Form validation and loading state
        document.getElementById('reset-password-form')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('txtPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Validate password length
            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                return;
            }

            // Validate password match
            if (password !== confirmPassword) {
                alert('Passwords do not match');
                return;
            }

            // Show loading state
            document.getElementById('submit-btn').disabled = true;
            document.getElementById('submit-text').innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Updating...';
            document.getElementById('loading-spinner').classList.remove('d-none');

            // Submit the form
            this.submit();
        });

        // Console logging for debugging flash data
        console.log('Flash Data:', <?= json_encode(session()->getFlashdata()) ?>);
    </script>
</body>

</html>