document.addEventListener('DOMContentLoaded', function() {
    const checkDomainBtn = document.getElementById('checkDomain');
    const domainInput = document.getElementById('txtDomain');
    const domainHelp = document.getElementById('domainHelp');
    const domainFeedback = document.getElementById('domainFeedback');
    const descriptionInput = document.getElementById('description');
    const jsonSettingsInput = document.getElementById('jsonSettings');
    const tenantForm = document.getElementById('editTenantForm');
    const domainRegex = /^[a-z0-9][a-z0-9\-]*[a-z0-9]$/;
    const fileInput = document.getElementById('txtLogo');
    const removeLogoFlag = document.getElementById('removeLogo');

    // Form validation
    if (tenantForm) {
        tenantForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const requiredFields = ['txtTenantName', 'intServiceTypeID'];
            let isValid = true;
            
            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field && !field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });

            // If domain input exists and is not readonly (create form)
            if (domainInput && !domainInput.readOnly && !domainRegex.test(domainInput.value)) {
                domainInput.classList.add('is-invalid');
                isValid = false;
            }

            // File validation
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

                if (file.size > maxSize) {
                    showAlert('error', 'File size exceeds 2MB limit.');
                    isValid = false;
                }

                if (!allowedTypes.includes(file.type)) {
                    showAlert('error', 'Invalid file type. Only JPG, PNG, and GIF files are allowed.');
                    isValid = false;
                }
            }

            if (isValid) {
                // Show loading state
                const submitBtn = tenantForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Saving...';

                // Prepare form data
                const formData = new FormData(tenantForm);

                // Debug log FormData contents
                console.log('Form data contents:');
                for (const [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        console.log(key, ':', value.name, value.size, 'bytes');
                    } else {
                        console.log(key, ':', value);
                    }
                }

                // Update JSON settings if description exists
                if (descriptionInput && jsonSettingsInput) {
                    try {
                        const currentSettings = JSON.parse(jsonSettingsInput.value || '{}');
                        currentSettings.description = descriptionInput.value;
                        formData.set('jsonSettings', JSON.stringify(currentSettings));
                    } catch (e) {
                        console.error('Error updating settings:', e);
                    }
                }

                // Handle checkbox values
                const bitActiveCheckbox = document.getElementById('bitActive');
                if (bitActiveCheckbox) {
                    formData.set('bitActive', bitActiveCheckbox.checked ? '1' : '0');
                }

                // Submit form
                fetch(tenantForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Failed to parse JSON response:', text);
                            return text;
                        }
                    });
                })
                .then(result => {
                    if (typeof result === 'object') {
                        if (result.success) {
                            window.location.href = result.redirect || '/tenants';
                        } else {
                            showAlert('error', result.error || Object.values(result.errors || {}).join('<br>'));
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    } else if (result.includes('success-message')) {
                        window.location.href = '/tenants';
                    } else if (result.includes('alert-danger')) {
                        const temp = document.createElement('div');
                        temp.innerHTML = result;
                        const error = temp.querySelector('.alert-danger');
                        showAlert('error', error ? error.innerHTML : 'An error occurred while saving.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    } else {
                        // If no specific error found, submit form normally
                        tenantForm.submit();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'An error occurred while submitting the form. ' + error.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            } else {
                showAlert('error', 'Please fill in all required fields correctly.');
            }
        });
    }

    // Domain name validation
    if (domainInput && checkDomainBtn) {
        domainInput.addEventListener('input', function(e) {
            const value = e.target.value.toLowerCase();
            if (value && !domainRegex.test(value)) {
                domainInput.classList.add('is-invalid');
                domainHelp.classList.add('text-danger');
            } else {
                domainInput.classList.remove('is-invalid');
                domainHelp.classList.remove('text-danger');
            }
        });

        checkDomainBtn.addEventListener('click', function() {
            const domain = domainInput.value.toLowerCase();
            
            if (!domain) {
                showDomainFeedback('warning', 'Please enter a domain name first.');
                return;
            }

            if (!domainRegex.test(domain)) {
                showDomainFeedback('error', 'Invalid domain format. Use only letters, numbers, and hyphens.');
                return;
            }

            checkDomainBtn.disabled = true;
            checkDomainBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Checking...';

            // Get base URL from form action if not defined globally
            const formBaseUrl = typeof baseUrl !== 'undefined' ? baseUrl : window.location.origin;
            fetch(`${formBaseUrl}/tenants/check-subdomain?subdomain=${domain}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        showDomainFeedback('success', `Domain "${data.normalized}" is available!`);
                        domainInput.value = data.normalized;
                    } else {
                        showDomainFeedback('error', `Domain "${data.normalized}" is already taken.`);
                    }
                })
                .catch(error => {
                    showDomainFeedback('error', 'Error checking domain availability. Please try again.');
                    console.error('Error:', error);
                })
                .finally(() => {
                    checkDomainBtn.disabled = false;
                    checkDomainBtn.innerHTML = '<i class="bi bi-search"></i> Check Availability';
                });
        });
    }

    // Initialize tooltips
    const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Helper function to show domain check feedback
    function showDomainFeedback(type, message) {
        const alertClass = type === 'success' ? 'alert-success' :
                         type === 'warning' ? 'alert-warning' : 'alert-danger';
        const icon = type === 'success' ? 'bi-check-circle' :
                    type === 'warning' ? 'bi-exclamation-triangle' : 'bi-x-circle';
        
        domainFeedback.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bi ${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    // Helper function to show general alerts
    function showAlert(type, message) {
        // Remove any existing alerts first
        const existingAlerts = document.querySelectorAll('.alert:not(#domainFeedback .alert)');
        existingAlerts.forEach(alert => alert.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show mb-3`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            <i class="bi bi-${type === 'error' ? 'x-circle' : 'check-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const formStart = tenantForm.querySelector('.card');
        if (formStart) {
            formStart.parentNode.insertBefore(alertDiv, formStart);
        } else {
            tenantForm.insertBefore(alertDiv, tenantForm.firstChild);
        }

        // Auto-dismiss after 5 seconds for success messages
        if (type !== 'error') {
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    }
});
