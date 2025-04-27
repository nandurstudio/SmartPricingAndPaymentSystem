<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/npm/@docsearch/css@3.css'); ?>">
</head>

<body class="text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"></path>
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path>
            <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"></path>
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
        </symbol>
    </svg>
    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (dark)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                <use href="#moon-stars-fill"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#sun-fill"></use>
                    </svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="dark" aria-pressed="true">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#moon-stars-fill"></use>
                    </svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#circle-half"></use>
                    </svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
        </ul>
    </div>
    <div class="modal modal-sheet position-static d-block bg-body-secondary p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2">Sign up for free</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Success Message -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <main class="form-signin w-100 m-auto">
                    <form method="post" action="/register/createUser">
                        <div class="modal-body p-5 pt-0">

                            <!-- Error messages -->
                            <!-- Username Error -->
                            <div id="usernameError" class="alert alert-danger" style="display: none;"></div>
                            <!-- Email Error -->
                            <div id="emailError" class="alert alert-danger" style="display: none;"></div>
                            <!-- Username -->
                            <div class="form-floating mb-3">
                                <input type="text" name="txtUserName" class="form-control" id="username" placeholder="Username" required minlength="4" maxlength="50" value="<?= old('txtUserName') ?>">
                                <label for="username">Username</label>
                                <?php if (isset($validation) && $validation->hasError('txtUserName')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('txtUserName'); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Full Name -->
                            <div class="form-floating mb-3">
                                <input type="text" name="txtFullName" class="form-control" id="fullName" placeholder="Full Name" required minlength="3" maxlength="100" value="<?= old('txtFullName') ?>">
                                <label for="fullName">Full Name</label>
                                <?php if (isset($validation) && $validation->hasError('txtFullName')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('txtFullName'); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input type="email" name="txtEmail" class="form-control" id="email" placeholder="Email" required maxlength="100" value="<?= old('txtEmail') ?>">
                                <label for="email">Email address</label>
                                <?php if (isset($validation) && $validation->hasError('txtEmail')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('txtEmail'); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input type="password" name="txtPassword" class="form-control" id="password" placeholder="Password" required minlength="6" maxlength="255">
                                <label for="password">Password</label>
                                <?php if (isset($validation) && $validation->hasError('txtPassword')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('txtPassword'); ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Photo (hidden) -->
                            <input type="hidden" name="txtPhoto" value="default.png">

                            <!-- Role (hidden) -->
                            <input type="hidden" name="intRoleID" value="5">

                            <!-- Active (hidden, default active) -->
                            <input type="hidden" name="bitActive" value="1">

                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary d-flex justify-content-center align-items-center" type="submit" id="submitBtn">
                                <span id="btnText">Sign up</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                            </button>

                            <small class="text-body-secondary">By clicking Sign up, you agree to the terms of use.</small>

                            <button class="btn btn-danger w-100 py-2 mt-3" type="button" id="googleBtn" onclick="window.location='<?php echo base_url('/auth/googleLogin'); ?>'">
                                <i class="bi bi-google"></i> Sign up with Google
                            </button>
                        </div>
                    </form>

                    <!-- Link to sign in -->
                    <p class="mt-3">
                        Already registered? <a href="/login">Sign in here</a>
                    </p>
                    <p class="mt-3 mb-3 text-muted">&copy; 2025 Kelompok 5</p>
                </main>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/register/register.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>