<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Register Your Business - BookingApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            color: #6c757d;
            font-weight: bold;
        }
        .step.active {
            background: #0d6efd;
            color: white;
        }
        .step.completed {
            background: #198754;
            color: white;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .service-type-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .service-type-card:hover {
            border-color: #0d6efd;
            transform: translateY(-2px);
        }
        .service-type-card.selected {
            border-color: #0d6efd;
            background: #f8f9fa;
        }
    </style>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center">
                                    <h3 class="fw-light my-4">Create Your Business Account</h3>
                                    
                                    <!-- Step Indicator -->
                                    <div class="step-indicator">
                                        <div class="step active" id="step1">1</div>
                                        <div class="step" id="step2">2</div>
                                        <div class="step" id="step3">3</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form id="registrationForm" action="<?= base_url('register/createUser') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <!-- Step 1: Personal Information -->
                                        <div class="form-step active" id="formStep1">
                                            <h5 class="mb-3">Personal Information</h5>
                                            
                                            <div class="mb-3">
                                                <label class="small mb-1" for="txtFullName">Full Name *</label>
                                                <input class="form-control" id="txtFullName" name="txtFullName" type="text" required />
                                                <div class="invalid-feedback">Please enter your full name.</div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="small mb-1" for="txtUserName">Username *</label>
                                                <input class="form-control" id="txtUserName" name="txtUserName" type="text" required />
                                                <div class="invalid-feedback">Please enter a username.</div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="small mb-1" for="txtEmail">Email *</label>
                                                <input class="form-control" id="txtEmail" name="txtEmail" type="email" required />
                                                <div class="invalid-feedback">Please enter a valid email address.</div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="small mb-1" for="phone">Phone Number *</label>
                                                <input class="form-control" id="phone" name="phone" type="tel" required />
                                                <div class="invalid-feedback">Please enter your phone number.</div>
                                            </div>
                                            
                                            <div class="row gx-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="txtPassword">Password *</label>
                                                        <div class="input-group">
                                                            <input class="form-control" id="txtPassword" name="txtPassword" type="password" required />
                                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <div class="invalid-feedback">Please enter a password.</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="small mb-1" for="passwordConfirm">Confirm Password *</label>
                                                        <div class="input-group">
                                                            <input class="form-control" id="passwordConfirm" name="password_confirm" type="password" required />
                                                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <div class="invalid-feedback">Passwords do not match or are empty.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="small mb-1" for="intRoleID">Role *</label>
                                                <select class="form-select" id="intRoleID" name="intRoleID" required>
                                                    <!-- Assuming Role ID 1 is a default 'User' role. This should ideally be populated from the database or a config file -->
                                                    <option value="1" selected>User</option>
                                                    <option value="2">Administrator (Example)</option> 
                                                </select>
                                                <div class="invalid-feedback">Please select a role.</div>
                                            </div>
                                            
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="bitActive" name="bitActive" type="checkbox" value="1" checked>
                                                <label class="form-check-label" for="bitActive">Active Account</label>
                                            </div>
                                            
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-primary" onclick="nextStep(2)">Next</button>
                                            </div>
                                        </div>

                                        <!-- Step 2: Business Information -->
                                        <div class="form-step" id="formStep2">
                                            <h5 class="mb-3">Business Information</h5>
                                            <div class="mb-3">
                                                <label class="small mb-1" for="businessName">Business Name *</label>
                                                <input class="form-control" id="businessName" name="business_name" type="text" required />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="small mb-1" for="businessSlug">Business URL *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><?= base_url(); ?></span>
                                                    <input class="form-control" id="businessSlug" name="business_slug" type="text" required />
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="form-text">This will be your unique business URL</div>
                                                <div id="slugAvailability" class="mt-2"></div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">Previous</button>
                                                <button type="button" class="btn btn-primary" onclick="nextStep(3)">Next</button>
                                            </div>
                                        </div>

                                        <!-- Step 3: Service Type Selection -->
                                        <div class="form-step" id="formStep3">
                                            <h5 class="mb-3">Choose Your Service Type</h5>
                                            <div class="row">
                                                <?php foreach ($serviceTypes as $serviceType): ?>
                                                <div class="col-md-6 mb-3">
                                                    <div class="service-type-card card h-100" data-service-type="<?= $serviceType['id']; ?>">
                                                        <div class="card-body text-center">
                                                            <i class="<?= $serviceType['icon'] ?? 'fas fa-cog'; ?> fa-3x text-primary mb-3"></i>
                                                            <h6 class="card-title"><?= esc($serviceType['name']); ?></h6>
                                                            <p class="card-text small text-muted"><?= esc($serviceType['description']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                                
                                                <!-- Custom Service Type Option -->
                                                <div class="col-md-6 mb-3">
                                                    <div class="service-type-card card h-100" data-service-type="custom">
                                                        <div class="card-body text-center">
                                                            <i class="fas fa-plus-circle fa-3x text-success mb-3"></i>
                                                            <h6 class="card-title">Request New Type</h6>
                                                            <p class="card-text small text-muted">Don't see your service type? Request a new one!</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" id="serviceTypeId" name="service_type_id" required>

                                            <!-- Custom Service Type Form -->
                                            <div id="customServiceForm" class="mt-4" style="display: none;">
                                                <div class="card border-success">
                                                    <div class="card-body">
                                                        <h6 class="card-title text-success">Request New Service Type</h6>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="customServiceName">Service Name *</label>
                                                            <input class="form-control" id="customServiceName" name="custom_service_name" type="text" />
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="customServiceCategory">Category *</label>
                                                            <select class="form-select" id="customServiceCategory" name="custom_service_category">
                                                                <option value="">Select Category</option>
                                                                <option value="sports">Sports & Recreation</option>
                                                                <option value="accommodation">Accommodation</option>
                                                                <option value="beauty">Beauty & Wellness</option>
                                                                <option value="education">Education & Training</option>
                                                                <option value="entertainment">Entertainment</option>
                                                                <option value="health">Health & Medical</option>
                                                                <option value="professional">Professional Services</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="small mb-1" for="customServiceDescription">Description *</label>
                                                            <textarea class="form-control" id="customServiceDescription" name="custom_service_description" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-check mt-3">
                                                <input class="form-check-input" id="terms" name="terms" type="checkbox" required />
                                                <label class="form-check-label" for="terms">
                                                    I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                                                </label>
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <div class="d-flex justify-content-between mt-4">
                                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">Previous</button>
                                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                                    <span class="spinner-border spinner-border-sm d-none" id="submitSpinner"></span>
                                                    Create Account
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">
                                        Already have an account? <a href="<?= base_url('auth/login'); ?>">Sign in here</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentStep = 1;
        let selectedServiceType = null;

        // Function to toggle password visibility
        function togglePasswordVisibility(inputId, toggleButtonId) {
            const passwordInput = document.getElementById(inputId);
            const toggleButton = document.getElementById(toggleButtonId);
            const icon = toggleButton.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordBtn = document.getElementById('togglePassword');
            if(togglePasswordBtn) {
                togglePasswordBtn.addEventListener('click', function () {
                    togglePasswordVisibility('txtPassword', 'togglePassword');
                });
            }

            const togglePasswordConfirmBtn = document.getElementById('togglePasswordConfirm');
            if(togglePasswordConfirmBtn) {
                togglePasswordConfirmBtn.addEventListener('click', function () {
                    togglePasswordVisibility('passwordConfirm', 'togglePasswordConfirm');
                });
            }
        });


        function nextStep(step) {
            if (validateCurrentStep()) {
                document.getElementById('formStep' + currentStep).classList.remove('active');
                document.getElementById('step' + currentStep).classList.remove('active');
                document.getElementById('step' + currentStep).classList.add('completed');
                
                currentStep = step;
                
                document.getElementById('formStep' + currentStep).classList.add('active');
                document.getElementById('step' + currentStep).classList.add('active');
            }
        }

        function prevStep(step) {
            document.getElementById('formStep' + currentStep).classList.remove('active');
            document.getElementById('step' + currentStep).classList.remove('active');
            
            if (currentStep > 1) {
                document.getElementById('step' + currentStep).classList.remove('completed');
            }
            
            currentStep = step;
            
            document.getElementById('formStep' + currentStep).classList.add('active');
            document.getElementById('step' + currentStep).classList.add('active');
        }

        function validateCurrentStep() {
            const form = document.getElementById('registrationForm');
            const currentStepElement = document.getElementById('formStep' + currentStep);
            const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Additional validation for specific steps
            if (currentStep === 1) {
                const password = document.getElementById('txtPassword').value;
                const confirmPassword = document.getElementById('passwordConfirm').value;
                const passwordConfirmInput = document.getElementById('passwordConfirm');
                // Corrected selector for the invalid-feedback div associated with passwordConfirm
                const passwordConfirmFeedback = passwordConfirmInput.closest('.input-group').nextElementSibling;

                if (!password) {
                    document.getElementById('txtPassword').classList.add('is-invalid');
                    isValid = false;
                } else {
                    document.getElementById('txtPassword').classList.remove('is-invalid');
                }

                if (!confirmPassword) {
                    passwordConfirmInput.classList.add('is-invalid');
                    if(passwordConfirmFeedback) passwordConfirmFeedback.textContent = 'Please confirm your password.';
                    isValid = false;
                } else if (password !== confirmPassword) {
                    passwordConfirmInput.classList.add('is-invalid');
                    if(passwordConfirmFeedback) passwordConfirmFeedback.textContent = 'Passwords do not match.';
                    isValid = false;
                } else {
                    passwordConfirmInput.classList.remove('is-invalid');
                     if(passwordConfirmFeedback) passwordConfirmFeedback.textContent = ''; // Clear message
                }
            }

            if (currentStep === 3 && !selectedServiceType) {
                alert('Please select a service type');
                isValid = false;
            }

            return isValid;
        }

        // Service type selection
        document.querySelectorAll('.service-type-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selection from all cards
                document.querySelectorAll('.service-type-card').forEach(c => c.classList.remove('selected'));
                
                // Add selection to clicked card
                this.classList.add('selected');
                
                const serviceType = this.dataset.serviceType;
                selectedServiceType = serviceType;
                
                if (serviceType === 'custom') {
                    document.getElementById('customServiceForm').style.display = 'block';
                    document.getElementById('serviceTypeId').removeAttribute('required');
                } else {
                    document.getElementById('customServiceForm').style.display = 'none';
                    document.getElementById('serviceTypeId').value = serviceType;
                    document.getElementById('serviceTypeId').setAttribute('required', 'required');
                }
            });
        });

        // Business slug auto-generation and validation
        document.getElementById('businessName').addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            
            document.getElementById('businessSlug').value = slug;
            checkSlugAvailability(slug);
        });

        document.getElementById('businessSlug').addEventListener('input', function() {
            checkSlugAvailability(this.value);
        });

        function checkSlugAvailability(slug) {
            if (slug.length < 3) return;
            
            fetch(`<?= base_url('auth/check-slug'); ?>?slug=${slug}`)
                .then(response => response.json())
                .then(data => {
                    const availabilityDiv = document.getElementById('slugAvailability');
                    if (data.available) {
                        availabilityDiv.innerHTML = '<small class="text-success"><i class="fas fa-check"></i> Available</small>';
                        document.getElementById('businessSlug').classList.remove('is-invalid');
                    } else {
                        availabilityDiv.innerHTML = '<small class="text-danger"><i class="fas fa-times"></i> Not available</small>';
                        document.getElementById('businessSlug').classList.add('is-invalid');
                    }
                });
        }

        // Form submission
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateCurrentStep()) return;
            
            const submitBtn = document.getElementById('submitBtn');
            const spinner = document.getElementById('submitSpinner');
            
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            const formData = new FormData(this);
            
            // Handle custom service type submission
            if (selectedServiceType === 'custom') {
                // First submit custom service type request
                const customServiceData = new FormData();
                customServiceData.append('name', formData.get('custom_service_name'));
                customServiceData.append('category', formData.get('custom_service_category'));
                customServiceData.append('description', formData.get('custom_service_description'));
                
                fetch('<?= base_url('auth/request-service-type'); ?>', {
                    method: 'POST',
                    body: customServiceData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Continue with registration using a default service type
                        formData.set('service_type_id', '1'); // Use first available service type
                        submitRegistration(formData);
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    alert('Error submitting custom service type: ' + error.message);
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                });
            } else {
                submitRegistration(formData);
            }
        });

        function submitRegistration(formData) {
            fetch('<?= base_url('auth/register'); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Registration successful! Redirecting to dashboard...');
                    window.location.href = data.redirect;
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = data.errors[field];
                                }
                            } else if (field === 'password_confirm') { // Handle password confirm error specifically if needed
                                const input = document.querySelector('[name="password_confirm"]');
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.closest('.input-group').nextElementSibling;
                                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                                        feedback.textContent = data.errors[field];
                                    }
                                }
                            }
                        });
                    } else {
                        alert('Registration failed: ' + data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during registration');
            })
            .finally(() => {
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('submitSpinner').classList.add('d-none');
            });
        }

        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const form = this;
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
            } else {
                const submitBtn = document.getElementById('submitBtn');
                const btnText = document.getElementById('btnText');
                const btnSpinner = document.getElementById('btnSpinner');

                submitBtn.disabled = true;
                btnText.textContent = 'Registering...';
                btnSpinner.classList.remove('d-none');
            }
        });
    </script>
</body>
</html>
