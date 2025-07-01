<?= $this->include('layouts/head') ?>

<body class="bg-light">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-8">
                            <!-- Modern forgot password form-->
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header bg-primary text-center py-4">
                                    <h3 class="fw-bold text-white my-2"><i class="bi bi-key-fill me-2 text-white"></i>Password Recovery</h3>
                                </div>
                                <div class="card-body p-4">
                                    <div class="text-center mb-4">
                                        <div class="avatar-icon mb-3">
                                            <i class="bi bi-envelope-paper text-primary" style="font-size: 3rem;"></i>
                                        </div>
                                        <div class="mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
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
                                        <form action="<?= base_url('auth/sendResetLink') ?>" method="post" id="forgot-password-form">
                                            <?= csrf_field() ?>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="email" type="email" name="email" placeholder="name@example.com" value="<?= old('email') ?>" required />
                                                <label for="email"><i class="bi bi-envelope me-2"></i>Email address</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small text-decoration-none" href="/login"><i class="bi bi-arrow-left me-1"></i>Return to login</a>
                                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                                    <span id="submit-text"><i class="bi bi-send me-2"></i>Send Reset Link</span>
                                                    <span id="loading-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <div class="text-center mt-4">
                                            <a href="/login" class="btn btn-primary"><i class="bi bi-arrow-left me-2"></i>Return to Login</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer text-center py-3 bg-light">
                                    <div class="small">
                                        <a href="/register" class="text-decoration-none">
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
    <!-- jQuery First -->
    <script src="<?= base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/scripts.js'); ?>"></script>
    <script>
        // Menghapus pesan error ketika input email di-fokuskan
        var emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('focus', function() {
                var errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            });
        }

        // Loading spinner saat submit
        var forgotForm = document.getElementById('forgot-password-form');
        if (forgotForm) {
            forgotForm.addEventListener('submit', function() {
                var submitBtn = document.getElementById('submit-btn');
                var submitText = document.getElementById('submit-text');
                var spinner = document.getElementById('loading-spinner');
                if (submitBtn && submitText && spinner) {
                    submitBtn.disabled = true;
                    submitText.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
                    spinner.classList.remove('d-none');
                }
            });
        }

        // Console logging for debugging flash data
        console.log('Flash Data:', <?= json_encode(session()->getFlashdata()) ?>);
    </script>

</body>

</html>