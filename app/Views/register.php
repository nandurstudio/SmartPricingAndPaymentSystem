<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
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

                <main class="form-signin w-100 m-auto">
                    <form method="post" action="/register/createUser">
                        <div class="modal-body p-5 pt-0">
                            <!-- Full Name -->
                            <div class="form-floating mb-3">
                                <input type="text" name="txtFullName" class="form-control" id="fullName" placeholder="Full Name" required minlength="3" maxlength="50">
                                <label for="fullName">Full Name</label>
                                <div class="invalid-feedback">
                                    Full Name is required and should be between 3-50 characters.
                                </div>
                            </div>

                            <!-- Nickname -->
                            <div class="form-floating mb-3">
                                <input type="text" name="txtNick" class="form-control <?= isset($validation) && $validation->hasError('txtNick') ? 'is-invalid' : '' ?>" id="nick" placeholder="Nick" value="<?= set_value('txtNick') ?>" maxlength="3">
                                <label for="nick">Nickname (max 3 characters)</label>
                                <?php if (isset($validation) && $validation->hasError('txtNick')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('txtNick') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Employee ID -->
                            <div class="form-floating mb-3">
                                <input type="text" name="txtEmpID" class="form-control" id="empID" placeholder="Employee ID" required pattern="[a-zA-Z0-9]+" minlength="5" maxlength="10">
                                <label for="empID">Employee ID</label>
                                <div class="invalid-feedback">
                                    Employee ID is required and should be a numeric value with 5-10 digits.
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="form-floating mb-3">
                                <input type="text" name="txtUserName" class="form-control" id="username" placeholder="Username" required minlength="4" maxlength="20">
                                <label for="username">Username</label>
                                <div class="invalid-feedback">
                                    Username is required and should be between 4-20 characters.
                                </div>
                            </div>

                            <!-- Email (optional) -->
                            <div class="form-floating mb-3">
                                <input type="email" name="txtEmail" class="form-control rounded-3" id="email" placeholder="name@kalbenutritionals.com" required>
                                <label for="email">Email address</label>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input type="password" name="txtPassword" class="form-control rounded-3" id="password" placeholder="Password" required minlength="6">
                                <label for="password">Password</label>
                                <div class="invalid-feedback">
                                    Password is required and must be at least 6 characters long.
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="form-floating mb-3">
                                <select name="intRoleID" class="form-control <?= isset($validation) && $validation->hasError('intRoleID') ? 'is-invalid' : '' ?>" id="role" required>
                                    <option value="">Pilih Role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['intRoleID']; ?>" <?= set_select('intRoleID', $role['intRoleID']); ?>><?= $role['txtRoleName']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="role">Role</label>
                                <?php if (isset($validation) && $validation->hasError('intRoleID')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('intRoleID') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Active -->
                            <div class="form-check mb-3 text-start">
                                <input class="form-check-input" type="checkbox" name="bitActive" id="active" value="1" checked>
                                <label class="form-check-label" for="active">
                                    Active
                                </label>
                            </div>

                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Sign up</button>
                            <small class="text-body-secondary">By clicking Sign up, you agree to the terms of use.</small>

                            <!-- Sign up with Microsoft -->
                            <button class="w-100 py-2 mb-2 btn btn-outline-primary rounded-3" type="button" onclick="window.location.href='https://login.microsoftonline.com'">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft Logo" width="20" height="20" class="me-1">
                                Sign up with Microsoft
                            </button>
                        </div>
                    </form>

                    <!-- Link to sign in -->
                    <p class="mt-3">
                        Already registered? <a href="/login">Sign in here</a>
                    </p>
                    <p class="mt-3 mb-3 text-muted">&copy; 2024</p>
                </main>
            </div>
        </div>
    </div>

</body>

</html>