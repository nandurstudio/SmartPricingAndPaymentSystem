<?php
$isEdit = isset($tenant);
$baseUrl = 'https://' . trim(service('request')->getServer('HTTP_HOST'), '/');
?>

<!-- Basic Information -->
<div class="row mb-3">
    <div class="col-md-6">
        <label for="txtTenantName" class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="txtTenantName" name="txtTenantName" 
               value="<?= old('txtTenantName') ?? ($isEdit ? $tenant['txtTenantName'] : '') ?>" required>
    </div>
    <div class="col-md-6">
        <label for="intServiceTypeID" class="form-label">Type <span class="text-danger">*</span></label>
        <select class="form-select" id="intServiceTypeID" name="intServiceTypeID" required>
            <option value="" disabled>Select Tenant Type</option>
            <?php foreach ($serviceTypes as $type): ?>
                <option value="<?= $type['intServiceTypeID'] ?>" 
                    <?= (old('intServiceTypeID') ?? ($isEdit ? $tenant['intServiceTypeID'] : '')) == $type['intServiceTypeID'] ? 'selected' : '' ?>>
                    <?= esc($type['txtName']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<!-- Description -->
<?php
$settings = json_decode($isEdit ? ($tenant['jsonSettings'] ?? '{}') : '{}', true);
$description = $settings['description'] ?? '';
?>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?? $description ?></textarea>
    <input type="hidden" name="jsonSettings" id="jsonSettings" value='<?= old('jsonSettings') ?? ($isEdit ? $tenant['jsonSettings'] : '{}') ?>'>
</div>

<!-- Domain and Code -->
<div class="mb-3">
    <label for="txtDomain" class="form-label">Domain Name <span class="text-danger">*</span></label>
    <div class="input-group">
        <span class="input-group-text">https://</span>
        <input type="text" class="form-control" id="txtDomain" name="txtDomain" 
               value="<?= old('txtDomain') ?? ($isEdit ? $tenant['txtDomain'] : '') ?>"
               placeholder="your-domain-name"
               <?= $isEdit ? 'readonly' : '' ?>
               required>
        <span class="input-group-text">.<?= rtrim(preg_replace('#^https?://#', '', env('app.baseURL') ?: 'smartpricingandpaymentsystem.localhost.com'), '/') ?></span>
        <?php if (!$isEdit): ?>
        <button class="btn btn-outline-primary" type="button" id="checkDomain">Check Availability</button>
        <?php endif; ?>
    </div>
    <div class="form-text" id="domainHelp">Choose a unique subdomain for your tenant. Only letters, numbers and hyphens are allowed.</div>
    <div id="domainFeedback"></div>
</div>

<div class="mb-3">
    <label for="txtTenantCode" class="form-label">Tenant Code</label>
    <input type="text" class="form-control" id="txtTenantCode" name="txtTenantCode" 
           value="<?= old('txtTenantCode') ?? ($isEdit ? $tenant['txtTenantCode'] : strtoupper(substr(md5(time()), 0, 8))) ?>" readonly>
    <div class="form-text">This is your unique tenant code for API integration.</div>
</div>

<!-- Status and Settings -->
<div class="row mb-3">
    <div class="col-md-6">
        <label for="txtSubscriptionPlan" class="form-label">Subscription Plan</label>
        <select class="form-select" id="txtSubscriptionPlan" name="txtSubscriptionPlan">
            <option value="free" <?= (old('txtSubscriptionPlan') ?? ($isEdit ? $tenant['txtSubscriptionPlan'] : '')) == 'free' ? 'selected' : '' ?>>Free</option>
            <option value="basic" <?= (old('txtSubscriptionPlan') ?? ($isEdit ? $tenant['txtSubscriptionPlan'] : '')) == 'basic' ? 'selected' : '' ?>>Basic</option>
            <option value="premium" <?= (old('txtSubscriptionPlan') ?? ($isEdit ? $tenant['txtSubscriptionPlan'] : '')) == 'premium' ? 'selected' : '' ?>>Premium</option>
            <option value="enterprise" <?= (old('txtSubscriptionPlan') ?? ($isEdit ? $tenant['txtSubscriptionPlan'] : '')) == 'enterprise' ? 'selected' : '' ?>>Enterprise</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="txtStatus" class="form-label">Status</label>
        <select class="form-select" id="txtStatus" name="txtStatus">
            <option value="active" <?= (old('txtStatus') ?? ($isEdit ? $tenant['txtStatus'] : '')) == 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= (old('txtStatus') ?? ($isEdit ? $tenant['txtStatus'] : '')) == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            <option value="pending" <?= (old('txtStatus') ?? ($isEdit ? $tenant['txtStatus'] : '')) == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="suspended" <?= (old('txtStatus') ?? ($isEdit ? $tenant['txtStatus'] : '')) == 'suspended' ? 'selected' : '' ?>>Suspended</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="txtTheme" class="form-label">Theme</label>
        <select class="form-select" id="txtTheme" name="txtTheme">
            <option value="default" <?= (old('txtTheme') ?? ($isEdit ? $tenant['txtTheme'] : '')) == 'default' ? 'selected' : '' ?>>Default</option>
            <option value="dark" <?= (old('txtTheme') ?? ($isEdit ? $tenant['txtTheme'] : '')) == 'dark' ? 'selected' : '' ?>>Dark</option>
            <option value="light" <?= (old('txtTheme') ?? ($isEdit ? $tenant['txtTheme'] : '')) == 'light' ? 'selected' : '' ?>>Light</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="bitActive" class="form-label">Active Status</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="bitActive" name="bitActive" value="1" 
                   <?= (old('bitActive') ?? ($isEdit ? $tenant['bitActive'] : '1')) == 1 ? 'checked' : '' ?>>
            <label class="form-check-label" for="bitActive">Active</label>
        </div>
        <small class="text-muted">Toggle to activate or deactivate the tenant</small>
    </div>
</div>

<!-- Logo Upload -->
<div class="row mb-4">
    <div class="col-md-4">
        <label for="txtLogo" class="form-label">Business Logo</label>
        <div class="logo-preview-wrapper mb-3 text-center">
            <?php if ($isEdit && !empty($tenant['txtLogo'])): ?>
                <img src="<?= base_url('uploads/tenants/' . $tenant['txtLogo']) ?>" 
                     alt="Current Logo" 
                     id="currentLogo"
                     class="img-thumbnail"
                     style="max-height: 150px; width: auto;">
            <?php else: ?>
                <div class="logo-placeholder rounded bg-light d-flex align-items-center justify-content-center"
                     style="height: 150px; width: 150px; margin: 0 auto;">
                    <i class="fas fa-building text-secondary" style="font-size: 3rem;"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <div class="input-group">
                <input type="file" class="form-control" id="txtLogo" name="txtLogo" accept="image/*">
                <?php if ($isEdit && !empty($tenant['txtLogo'])): ?>
                    <button class="btn btn-outline-danger" type="button" id="removeLogo">
                        <i class="fas fa-trash"></i>
                    </button>
                <?php endif; ?>
            </div>
            <div class="form-text">
                Recommended size: 200x200px, Max: 2MB. Supported formats: JPG, PNG, GIF
            </div>
        </div>
    </div>
</div>

<!-- Form Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle description and jsonSettings sync
    document.getElementById('description').addEventListener('input', function() {
        const description = this.value;
        let settings = {};
        try {
            settings = JSON.parse(document.getElementById('jsonSettings').value);
        } catch (e) {
            settings = {};
        }
        settings.description = description;
        document.getElementById('jsonSettings').value = JSON.stringify(settings);
    });

    // Initialize description from jsonSettings
    const jsonSettingsValue = document.getElementById('jsonSettings').value;
    if (jsonSettingsValue) {
        try {
            const settings = JSON.parse(jsonSettingsValue);
            if (settings.description) {
                document.getElementById('description').value = settings.description;
            }
        } catch (e) {
            console.error('Error parsing jsonSettings:', e);
        }
    }

    // Domain availability check
    const domainInput = document.getElementById('txtDomain');
    const checkButton = document.getElementById('checkDomain');
    const feedbackDiv = document.getElementById('domainFeedback');
    
    if (checkButton) {
        checkButton.addEventListener('click', function() {
            const subdomain = domainInput.value.trim();
            
            if (!subdomain) {
                showDomainFeedback('Please enter a subdomain', 'warning');
                return;
            }

            // Show loading state
            checkButton.disabled = true;
            checkButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking...';
            
            // Make AJAX request
            fetch(`/tenants/checkSubdomain?subdomain=${encodeURIComponent(subdomain)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        showDomainFeedback(data.message, 'success');
                        if (data.normalized !== subdomain) {
                            domainInput.value = data.normalized;
                        }
                    } else {
                        showDomainFeedback(data.message, 'danger');
                    }
                })
                .catch(error => {
                    showDomainFeedback('Error checking domain availability. Please try again.', 'danger');
                })
                .finally(() => {
                    // Reset button state
                    checkButton.disabled = false;
                    checkButton.innerHTML = 'Check Availability';
                });
        });

        // Helper function to show domain feedback
        function showDomainFeedback(message, type) {
            feedbackDiv.className = `alert alert-${type} mt-2`;
            feedbackDiv.innerHTML = message;
        }

        // Auto-normalize domain input
        domainInput.addEventListener('input', function() {
            this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '-');
        });
    }

    // Logo preview functionality
    const logoInput = document.getElementById('txtLogo');
    const logoPreviewWrapper = document.querySelector('.logo-preview-wrapper');

    logoInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Remove existing preview
                while (logoPreviewWrapper.firstChild) {
                    logoPreviewWrapper.removeChild(logoPreviewWrapper.firstChild);
                }
                
                // Create new preview
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Logo Preview';
                img.className = 'img-thumbnail';
                img.style.maxHeight = '150px';
                img.style.width = 'auto';
                logoPreviewWrapper.appendChild(img);
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Remove logo functionality
    const removeLogoBtn = document.getElementById('removeLogo');
    if (removeLogoBtn) {
        removeLogoBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the current logo?')) {
                // Create a hidden input to signal logo removal
                const removeLogoInput = document.createElement('input');
                removeLogoInput.type = 'hidden';
                removeLogoInput.name = 'removeLogo';
                removeLogoInput.value = '1';
                this.closest('form').appendChild(removeLogoInput);
                
                // Clear file input
                logoInput.value = '';
                
                // Update preview
                while (logoPreviewWrapper.firstChild) {
                    logoPreviewWrapper.removeChild(logoPreviewWrapper.firstChild);
                }
                
                // Show placeholder
                const placeholder = document.createElement('div');
                placeholder.className = 'logo-placeholder rounded bg-light d-flex align-items-center justify-content-center';
                placeholder.style.height = '150px';
                placeholder.style.width = '150px';
                placeholder.style.margin = '0 auto';
                placeholder.innerHTML = '<i class="fas fa-building text-secondary" style="font-size: 3rem;"></i>';
                logoPreviewWrapper.appendChild(placeholder);
                
                // Hide remove button
                this.style.display = 'none';
            }
        });
    }
});
</script>
