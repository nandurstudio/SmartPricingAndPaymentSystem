<?= $this->include('layouts/head') ?>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <!-- Basic forgot password form-->
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center">
                                    <h3 class="fw-light my-4">Reset Password</h3>
                                </div>
                                <div class="card-body">
                                    <div class="small mb-3 text-muted">Enter your new password.</div>

                                    <!-- Menampilkan pesan sukses atau error -->
                                    <?php if (session()->getFlashdata('success')) : ?>
                                        <div id="success-message" class="alert alert-success">
                                            <?= session()->getFlashdata('success') ?>
                                        </div>
                                    <?php elseif (session()->getFlashdata('error')) : ?>
                                        <div id="error-message" class="alert alert-danger">
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Form hanya ditampilkan jika tidak ada pesan sukses -->
                                    <?php if (!session()->getFlashdata('success')) : ?>
                                        <?= csrf_field() ?>
                                        <form action="<?= base_url('auth/updatePassword') ?>" method="post">
                                            <input type="hidden" name="token" value="<?= esc($token) ?>">
                                            <div class="mb-3 position-relative">
                                                <label class="small mb-1" for="txtPassword">New Password</label>
                                                <div class="input-group">
                                                    <input class="form-control" id="txtPassword" type="password" name="txtPassword" minlength="3" required />
                                                    <span class="input-group-text cursor-pointer" onclick="togglePassword('txtPassword', 'togglePasswordIcon')">
                                                        <i id="togglePasswordIcon" data-feather="eye"></i>
                                                    </span>
                                                </div>
                                                <small class="form-text text-muted">Password harus minimal 3 karakter.</small>
                                            </div>
                                            <div class="mb-3 position-relative">
                                                <label class="small mb-1" for="confirmPassword">Confirm Password</label>
                                                <div class="input-group">
                                                    <input class="form-control" id="confirmPassword" type="password" name="confirmPassword" minlength="3" required />
                                                    <span class="input-group-text cursor-pointer" onclick="togglePassword('confirmPassword', 'toggleConfirmPasswordIcon')">
                                                        <i id="toggleConfirmPasswordIcon" data-feather="eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="/login">Return to login</a>
                                                <button type="submit" class="btn btn-primary">Update Password</button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="/register">Need an account? Sign up!</a></div>
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
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/scripts.js'); ?>"></script>
    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            var password = document.getElementById("txtPassword").value;
            if (password.length < 3) {
                event.preventDefault(); // Mencegah form terkirim jika tidak memenuhi syarat
                alert("Password harus minimal 3 karakter.");
            }
        });

        function togglePassword(inputId, iconId) {
            var input = document.getElementById(inputId);
            var icon = document.getElementById(iconId);

            // Toggle tipe input antara password dan text
            if (input.type === "password") {
                input.type = "text";
                icon.setAttribute("data-feather", "eye-off"); // Ganti ikon ke eye-off
            } else {
                input.type = "password";
                icon.setAttribute("data-feather", "eye"); // Ganti ikon ke eye
            }

            // Muat ulang ikon Feather
            feather.replace();
        }
    </script>

</body>

</html>