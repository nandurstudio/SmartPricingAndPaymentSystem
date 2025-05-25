<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
  <script src="<?php echo base_url('/assets/js/color-modes.js'); ?>"></script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Smart Pricing and Payment System Login">
  <meta name="author" content="Kelompok 5">
  <title>Login - Smart Pricing System</title>

  <!-- Favicon -->
  <link rel="icon" href="<?php echo base_url('favicon.ico'); ?>" type="image/x-icon">
  
  <!-- CSS -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/login/sign-in.css'); ?>">

  <style>
    /* Additional custom styles */
    :root {
      --primary-color: #0d6efd;
      --primary-hover: #0b5ed7;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
      border-color: #86b7fe;
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-primary:hover {
      background-color: var(--primary-hover);
      border-color: var(--primary-hover);
    }

    .spinner-container {
      display: none;
      margin-left: 8px;
    }

    .alert {
      margin-top: 1rem;
      display: none;
    }

    .password-container {
      position: relative;
    }

    .eye-icon {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      z-index: 10;
      color: #6c757d;
      cursor: pointer;
    }

    .eye-icon:hover {
      color: #0d6efd;
    }
    
    .brand-section {
      display: flex;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    .brand-logo {
      width: 48px;
      height: 48px;
      margin-right: 12px;
    }
    
    .brand-text {
      flex: 1;
    }
    
    .brand-text h1 {
      margin: 0;
      font-size: 1.5rem;
      font-weight: 600;
      color: #212529;
    }
    
    .brand-text p {
      margin: 0;
      font-size: 0.875rem;
      color: #6c757d;
    }
    
    .social-divider {
      display: flex;
      align-items: center;
      margin: 1.5rem 0;
      color: #6c757d;
    }
    
    .social-divider:before,
    .social-divider:after {
      content: "";
      flex: 1;
      border-top: 1px solid #dee2e6;
    }
    
    .social-divider span {
      padding: 0 0.5rem;
    }
    
    .form-card {
      border-radius: 0.5rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      border: none;
      overflow: hidden;
    }
  </style>
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
  <!-- SVG Icons -->
  <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="check2" viewBox="0 0 16 16">
      <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
    </symbol>
    <symbol id="circle-half" viewBox="0 0 16 16">
      <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
    </symbol>
    <symbol id="moon-stars-fill" viewBox="0 0 16 16">
      <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
      <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
    </symbol>
    <symbol id="sun-fill" viewBox="0 0 16 16">
      <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
    </symbol>
  </svg>

  <!-- Theme Toggle -->
  <div class="dropdown position-fixed top-0 end-0 mt-3 me-3 bd-mode-toggle">
    <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
            id="bd-theme"
            type="button"
            aria-expanded="false"
            data-bs-toggle="dropdown"
            aria-label="Toggle theme (auto)">
      <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
      <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
      <li>
        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
          <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#sun-fill"></use></svg>
          Light
          <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
        </button>
      </li>
      <li>
        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
          <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
          Dark
          <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
        </button>
      </li>
      <li>
        <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
          <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#circle-half"></use></svg>
          Auto
          <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
        </button>
      </li>
    </ul>
  </div>

  <!-- Main Container -->
  <main class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card form-card p-4">          <!-- Brand Section -->
          <div class="brand-section">
            <img src="<?php echo base_url('assets/brand/bootstrap-logo.svg'); ?>" alt="Logo" class="brand-logo">
            <div class="brand-text">
              <h1>Smart Pricing System</h1>
              <p>Sign in to your account</p>
            </div>
          </div>
          
          <!-- Flash Messages -->
          <?= display_flash_messages() ?>
          
          <!-- Debugging Flash Messages -->
          <div id="debug-flash-messages" style="display: none;">
            <p>Session flashdata: 
              <?php 
              $flashData = session()->getFlashdata();
              echo !empty($flashData) ? print_r($flashData, true) : 'No flash data';
              ?>
            </p>
            <p>Raw Session: 
              <?php 
              echo !empty($_SESSION) ? print_r($_SESSION, true) : 'No session data';
              ?>
            </p>
          </div>          
          <?= display_flash_messages() ?>
          
          <!-- Debugging Flash Messages -->
          <div id="debug-flash-messages" style="display: none;">
            <p>Session flashdata: 
              <?php 
              $flashData = session()->getFlashdata();
              echo !empty($flashData) ? print_r($flashData, true) : 'No flash data';
              ?>
            </p>
            <p>Raw Session: 
              <?php 
              echo !empty($_SESSION) ? print_r($_SESSION, true) : 'No session data';
              ?>
            </p>
          </div>          <!-- Login Form -->
          <form id="loginForm" action="<?php echo base_url('/login'); ?>" method="post">
            <?= csrf_field() ?>
              <!-- Email or Username Field -->
            <div class="form-floating mb-3">
              <input 
                type="text" 
                class="form-control" 
                id="txtEmail" 
                name="txtEmail" 
                placeholder="Email address or Username" 
                required 
                autocomplete="username"
                autofocus
                value="<?= old('txtEmail') ?>"
              >
              <label for="txtEmail"><i class="bi bi-person me-2"></i>Email or Username</label>
              <div class="invalid-feedback" id="email-feedback"></div>
            </div>
            
            <!-- Password Field -->
            <div class="form-floating mb-3 password-container">
              <input 
                type="password" 
                class="form-control" 
                id="txtPassword" 
                name="txtPassword" 
                placeholder="Password" 
                required 
                autocomplete="current-password"
              >
              <label for="txtPassword"><i class="bi bi-lock me-2"></i>Password</label>
              <span id="toggle-password" class="eye-icon">
                <i class="bi bi-eye-slash"></i>
              </span>
              <div class="invalid-feedback" id="password-feedback"></div>
            </div>
            
            <!-- Remember Me -->
            <div class="d-flex justify-content-between mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me" value="1">
                <label class="form-check-label" for="remember_me">
                  Remember me
                </label>
              </div>
              <div>
                <a href="<?= base_url('auth/forgot_password'); ?>" class="text-decoration-none">Forgot password?</a>
              </div>
            </div>
            
            <!-- Login Button -->
            <button class="btn btn-primary w-100 py-2 d-flex justify-content-center align-items-center" type="submit" id="login-button">
              <span>Sign in</span>
              <div class="spinner-container" id="login-spinner">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              </div>
            </button>
            
            <!-- Error Alert -->
            <div class="alert alert-danger mt-3" role="alert" id="error-message">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              <span id="error-text"></span>
            </div>
            
            <!-- Social Login Divider -->
            <div class="social-divider">
              <span>or</span>
            </div>
            
            <!-- Google Login Button -->
            <button class="btn btn-outline-danger w-100 py-2 d-flex justify-content-center align-items-center" type="button" onclick="window.location='<?php echo base_url('/auth/googleLogin'); ?>'">
              <i class="bi bi-google me-2"></i>
              <span>Continue with Google</span>
            </button>
            
            <!-- Registration Link -->
            <div class="text-center mt-4">
              <span class="text-muted">Don't have an account?</span>
              <a href="<?= base_url('register'); ?>" class="text-decoration-none fw-medium ms-1">Sign up</a>
            </div>
          </form>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-muted mt-4 small">
          &copy; <?= date('Y') ?> Smart Pricing System - Kelompok 5
        </p>
      </div>
    </div>
  </main>

  <!-- JavaScript -->
  <script src="<?php echo base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
  <script>
    $(document).ready(function() {
      // Toggle password visibility
      $("#toggle-password").on("click", function() {
        const passwordInput = $("#txtPassword");
        const toggleIcon = $(this).find("i");
        
        if (passwordInput.attr("type") === "password") {
          passwordInput.attr("type", "text");
          toggleIcon.removeClass("bi-eye-slash").addClass("bi-eye");
        } else {
          passwordInput.attr("type", "password");
          toggleIcon.removeClass("bi-eye").addClass("bi-eye-slash");
        }
      });      // Form submission
      $("#loginForm").on("submit", function(e) {
        e.preventDefault();
        
        // Reset form states
        $(this).find(".is-invalid").removeClass("is-invalid");
        $("#error-message").hide();
        
        // Show loading state
        const loginButton = $("#login-button");
        const loginSpinner = $("#login-spinner");
        loginButton.prop("disabled", true);
        loginSpinner.show();
        
        // Form data
        const formData = $(this).serialize();
          // Log di console untuk debugging
        console.log("Form submission started");
        console.log("Form action URL:", "<?php echo base_url('/login'); ?>");
        
        // AJAX request
        $.ajax({
          url: "<?php echo base_url('/login'); ?>", // Gunakan URL yang terdaftar di Routes
          type: "POST",
          data: formData,
          dataType: "json",
          success: function(response) {
            console.log("Login success, response:", response);
            // Redirect on success
            window.location.href = response.redirect || "<?php echo base_url('/landing'); ?>";
          },          error: function(xhr, status, error) {
            // Log error details for debugging
            console.error("Login error:", status, error);
            console.error("Response:", xhr.responseText);
            console.error("Status code:", xhr.status);
            
            // Handle errors
            let errorMessage = "Login failed. Please try again.";
            
            if (xhr.status === 404) {
              errorMessage = "Server error: Login endpoint not found. Please contact administrator.";
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
              errorMessage = xhr.responseJSON.error;
              
              // Field-specific errors
              if (xhr.responseJSON.fields) {
                const fields = xhr.responseJSON.fields;
                
                if (fields.email) {
                  $("#txtEmail").addClass("is-invalid");
                  $("#email-feedback").text(fields.email);
                }
                
                if (fields.password) {
                  $("#txtPassword").addClass("is-invalid");
                  $("#password-feedback").text(fields.password);
                }
              }
            }
            
            // Show error message
            $("#error-text").text(errorMessage);
            $("#error-message").show();
          },
          complete: function() {
            // Reset loading state
            loginButton.prop("disabled", false);
            loginSpinner.hide();
          }
        });
      });
    });
  </script>
</body>
</html>
