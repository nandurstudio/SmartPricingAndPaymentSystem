<?php
$isEdit = isset($tenant);
$baseUrl = 'https://' . trim(service('request')->getServer('HTTP_HOST'), '/');
?>

<!-- Business Information Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-building me-1"></i> Business Information
        </h6>
    </div>
    <div class="card-body">
        <!-- Basic Information -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="txtTenantName" class="form-label">Business Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="txtTenantName" name="txtTenantName" 
                       value="<?= old('txtTenantName') ?? ($isEdit ? $tenant['txtTenantName'] : '') ?>" required>
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i> Enter your business or organization name
                </div>
            </div>
            <div class="col-md-6">
                <label for="intServiceTypeID" class="form-label">Business Type <span class="text-danger">*</span></label>
                <select class="form-select" id="intServiceTypeID" name="intServiceTypeID" required>
                    <option value="" disabled selected>Select Business Type</option>
                    <?php foreach ($serviceTypes as $type): ?>
                        <option value="<?= $type['intServiceTypeID'] ?>" 
                            <?= (old('intServiceTypeID') ?? ($isEdit ? $tenant['intServiceTypeID'] : '')) == $type['intServiceTypeID'] ? 'selected' : '' ?>>
                            <i class="bi bi-<?= esc($type['txtIcon']) ?>"></i> <?= esc($type['txtName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">
                    <i class="bi bi-tag me-1"></i> Choose the category that best describes your business
                </div>
            </div>
        </div>

        <!-- Description -->
        <?php
        $settings = json_decode($isEdit ? ($tenant['jsonSettings'] ?? '{}') : '{}', true);
        $description = $settings['description'] ?? '';
        ?>
        <div class="mb-3">
            <label for="description" class="form-label">Business Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" 
                      placeholder="Describe your business in a few sentences..."><?= old('description') ?? $description ?></textarea>
            <div class="form-text">
                <i class="bi bi-card-text me-1"></i> This description will be visible to your customers
            </div>
        </div>

        <!-- Domain and Code -->
        <div class="mb-3">
            <label for="txtDomain" class="form-label">Domain Name <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-globe"></i></span>
                <input type="text" class="form-control" id="txtDomain" name="txtDomain" 
                       value="<?= old('txtDomain') ?? ($isEdit ? $tenant['txtDomain'] : '') ?>"
                       placeholder="your-business-name"
                       pattern="[a-z0-9][a-z0-9\-]*[a-z0-9]"
                       <?= $isEdit ? 'readonly' : '' ?>
                       required>
                <span class="input-group-text">.<?= rtrim(preg_replace('#^https?://#', '', env('app.baseURL') ?: 'smartpricingandpaymentsystem.localhost.com'), '/') ?></span>
                <?php if (!$isEdit): ?>
                    <button class="btn btn-outline-primary" type="button" id="checkDomain">
                        <i class="bi bi-search"></i> Check Availability
                    </button>
                <?php endif; ?>
            </div>
            <div class="form-text" id="domainHelp">
                <i class="bi bi-info-circle"></i> Choose a unique subdomain - Only lowercase letters, numbers, and hyphens allowed.
                <br><i class="bi bi-link-45deg"></i> This will be your business's web address.
            </div>
            <div id="domainFeedback" class="mt-2"></div>
        </div>

        <div class="mb-3">
            <label for="txtTenantCode" class="form-label">Tenant Code</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input type="text" class="form-control" id="txtTenantCode" name="txtTenantCode" 
                       value="<?= old('txtTenantCode') ?? ($isEdit ? $tenant['txtTenantCode'] : strtoupper(substr(md5(time()), 0, 8))) ?>" readonly>
                <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(this.previousElementSibling.value)" title="Copy to clipboard">
                    <i class="bi bi-clipboard"></i>
                </button>
            </div>
            <div class="form-text">
                <i class="bi bi-shield-check"></i> Your unique tenant code for API integration and identification
            </div>
        </div>
    </div>
</div>

<!-- Subscription and Settings Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-gear me-1"></i> Subscription and Settings
        </h6>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="txtSubscriptionPlan" class="form-label">Subscription Plan</label>
                <select class="form-select" id="txtSubscriptionPlan" name="txtSubscriptionPlan">
                    <option value="free" <?= (old('txtSubscriptionPlan') ?? ($isEdit ? $tenant['txtSubscriptionPlan'] : '')) == 'free' ? 'selected' : '' ?>>
                        <i class="bi bi-star"></i> Free
                    </option>
                    <option value="basic" <?= (old('txtSubscriptionPlan') ?? ($isEdit ? $tenant['txtSubscriptionPlan'] : '')) == 'basic' ? 'selected' : '' ?>>
                        <i class="bi bi-star-half"></i> Basic
                    </option>
                    <option value="premium" <?= (old('txtSubscriptionPlan') ?? ($isEdit ? $tenant['txtSubscriptionPlan'] : '')) == 'premium' ? 'selected' : '' ?>>
                        <i class="bi bi-star-fill"></i> Premium
                    </option>
                    <option value="enterprise" <?= (old('txtSubscriptionPlan') ?? ($isEdit ? $tenant['txtSubscriptionPlan'] : '')) == 'enterprise' ? 'selected' : '' ?>>
                        <i class="bi bi-stars"></i> Enterprise
                    </option>
                </select>
                <div class="form-text">
                    <i class="bi bi-box me-1"></i> Choose a plan that fits your business needs
                </div>
            </div>
            <?php if ($isEdit && in_array(session()->get('role'), ['admin', 'super_admin'])): ?>
            <div class="col-md-6">
                <label for="txtStatus" class="form-label">Account Status</label>
                <select class="form-select" id="txtStatus" name="txtStatus">
                    <option value="active" <?= (old('txtStatus') ?? ($tenant['txtStatus'] ?? 'active')) == 'active' ? 'selected' : '' ?>>
                        Active
                    </option>
                    <option value="inactive" <?= (old('txtStatus') ?? ($tenant['txtStatus'] ?? '')) == 'inactive' ? 'selected' : '' ?>>
                        Inactive
                    </option>
                    <option value="suspended" <?= (old('txtStatus') ?? ($tenant['txtStatus'] ?? '')) == 'suspended' ? 'selected' : '' ?>>
                        Suspended
                    </option>
                    <option value="pending_verification" <?= (old('txtStatus') ?? ($tenant['txtStatus'] ?? '')) == 'pending_verification' ? 'selected' : '' ?>>
                        Pending Verification
                    </option>
                </select>
                <div class="form-text">
                    <i class="bi bi-shield-lock me-1"></i> Only administrators can change account status
                </div>
            </div>
            <?php else: ?>
            <input type="hidden" name="txtStatus" value="active">
            <?php endif; ?>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="txtTheme" class="form-label">Theme</label>
                <select class="form-select" id="txtTheme" name="txtTheme">
                    <option value="default" <?= (old('txtTheme') ?? ($isEdit ? $tenant['txtTheme'] : '')) == 'default' ? 'selected' : '' ?>>
                        <i class="bi bi-palette"></i> Default
                    </option>
                    <option value="dark" <?= (old('txtTheme') ?? ($isEdit ? $tenant['txtTheme'] : '')) == 'dark' ? 'selected' : '' ?>>
                        <i class="bi bi-moon"></i> Dark
                    </option>
                    <option value="light" <?= (old('txtTheme') ?? ($isEdit ? $tenant['txtTheme'] : '')) == 'light' ? 'selected' : '' ?>>
                        <i class="bi bi-sun"></i> Light
                    </option>
                </select>
                <div class="form-text">
                    <i class="bi bi-palette me-1"></i> Choose the appearance theme for your tenant portal
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label d-block">System Access</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="bitActive" name="bitActive" value="1" 
                           <?= (!$isEdit || (old('bitActive') ?? ($tenant['bitActive'] ?? '1')) == 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="bitActive">
                        Enable system access
                    </label>
                </div>
                <div class="form-text">
                    <i class="bi bi-shield-lock me-1"></i> Controls whether users can log in
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logo Upload Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-image me-1"></i> Business Logo
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
        <div class="logo-preview-wrapper mb-3 text-center">
            <div id="logoPreview">
                <?php if ($isEdit && !empty($tenant['txtLogo'])): ?>
                    <img src="<?= base_url('uploads/tenants/' . $tenant['txtLogo']) ?>" 
                         alt="Current Logo" 
                         id="currentLogo"
                         class="img-thumbnail"
                         style="max-height: 150px; width: auto;">
                <?php else: ?>
                    <div class="logo-placeholder rounded bg-light d-flex align-items-center justify-content-center"
                         style="height: 150px; width: 150px; margin: 0 auto;">
                        <i class="bi bi-building text-secondary" style="font-size: 3rem;"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>        <div class="mb-3">
            <div class="input-group">
                <input type="file" class="form-control" id="txtLogo" name="txtLogo" accept="image/jpeg,image/png,image/gif" onchange="previewImage(this)">
                <?php if ($isEdit && !empty($tenant['txtLogo'])): ?>
                    <input type="hidden" name="currentLogo" value="<?= $tenant['txtLogo'] ?>">
                    <button class="btn btn-outline-danger" type="button" id="removeLogo" title="Remove Logo">
                        <i class="bi bi-trash"></i>
                    </button>
                <?php endif; ?>
            </div>
            <div class="form-text">
                <i class="bi bi-info-circle me-1"></i> Recommended size: 200x200px, Max: 2MB
                <br>
                <i class="bi bi-file-earmark-image me-1"></i> Supported formats: JPG, PNG, GIF
            </div>
            <div id="logoUploadFeedback" class="invalid-feedback"></div>
        </div>

        <script>
            function previewImage(input) {
                const preview = document.getElementById('logoPreview');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.innerHTML = `
                            <img src="${e.target.result}" 
                                 alt="Logo Preview" 
                                 class="img-thumbnail"
                                 style="max-height: 150px; width: auto;">
                        `;
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.innerHTML = `
                        <div class="logo-placeholder rounded bg-light d-flex align-items-center justify-content-center"
                             style="height: 150px; width: 150px; margin: 0 auto;">
                            <i class="bi bi-building text-secondary" style="font-size: 3rem;"></i>
                        </div>
                    `;
                }
            }

            // Handle remove logo
            const removeLogo = document.getElementById('removeLogo');
            if (removeLogo) {
                removeLogo.addEventListener('click', function() {
                    // Clear file input
                    document.getElementById('txtLogo').value = '';
                    
                    // Add a flag to indicate logo removal
                    const removeLogoFlag = document.createElement('input');
                    removeLogoFlag.type = 'hidden';
                    removeLogoFlag.name = 'removeLogo';
                    removeLogoFlag.value = '1';
                    this.parentElement.appendChild(removeLogoFlag);
                    
                    // Update preview
                    document.getElementById('logoPreview').innerHTML = `
                        <div class="logo-placeholder rounded bg-light d-flex align-items-center justify-content-center"
                             style="height: 150px; width: 150px; margin: 0 auto;">
                            <i class="bi bi-building text-secondary" style="font-size: 3rem;"></i>
                        </div>
                    `;
                    
                    // Hide remove button
                    this.style.display = 'none';
                });
            }
        </script>
            </div>
        </div>
    </div>
</div>

<!-- Load tenant form specific script -->
<?= $this->section('scripts') ?>
<script>
    // Define base URL for JavaScript use
    const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/pages/tenant-form.js') ?>"></script>
<?= $this->endSection() ?>
