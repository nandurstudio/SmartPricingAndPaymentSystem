<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('tenant') ?>">Tenants</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            <?= $pageTitle ?>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4>Error:</h4>
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>            <form action="<?= base_url('tenant/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="txtTenantName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtTenantName" name="txtTenantName" value="<?= old('txtTenantName') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="intServiceTypeID" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="intServiceTypeID" name="intServiceTypeID" required>
                            <option value="" selected disabled>Select Tenant Type</option>
                            <?php foreach ($serviceTypes as $type): ?>
                                <option value="<?= $type['intServiceTypeID'] ?>" <?= old('intServiceTypeID') == $type['intServiceTypeID'] ? 'selected' : '' ?>>
                                    <?= esc($type['txtName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="jsonSettings" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= old('description') ?></textarea>
                    <input type="hidden" name="jsonSettings" id="jsonSettings" value="<?= old('jsonSettings') ?? json_encode(['description' => '']) ?>">
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="txtDomain" class="form-label">Domain <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtDomain" name="txtDomain" value="<?= old('txtDomain') ?>" placeholder="example.com">
                    </div>
                    <div class="col-md-6">
                        <label for="txtTenantCode" class="form-label">Tenant Code</label>
                        <input type="text" class="form-control" id="txtTenantCode" name="txtTenantCode" value="<?= old('txtTenantCode') ?? strtoupper(substr(md5(time()), 0, 8)) ?>" readonly>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="txtSubscriptionPlan" class="form-label">Subscription Plan</label>
                        <select class="form-select" id="txtSubscriptionPlan" name="txtSubscriptionPlan">
                            <option value="free" <?= old('txtSubscriptionPlan') == 'free' ? 'selected' : '' ?>>Free</option>
                            <option value="basic" <?= old('txtSubscriptionPlan') == 'basic' ? 'selected' : '' ?>>Basic</option>
                            <option value="premium" <?= old('txtSubscriptionPlan') == 'premium' ? 'selected' : '' ?>>Premium</option>
                            <option value="enterprise" <?= old('txtSubscriptionPlan') == 'enterprise' ? 'selected' : '' ?>>Enterprise</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="txtStatus" class="form-label">Status</label>
                        <select class="form-select" id="txtStatus" name="txtStatus">
                            <option value="active" <?= old('txtStatus') == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= old('txtStatus') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="pending" <?= old('txtStatus') == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="suspended" <?= old('txtStatus') == 'suspended' ? 'selected' : '' ?>>Suspended</option>
                        </select>
                    </div>
                </div>
                  <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="txtTheme" class="form-label">Theme</label>
                        <select class="form-select" id="txtTheme" name="txtTheme">
                            <option value="default" <?= old('txtTheme') == 'default' ? 'selected' : '' ?>>Default</option>
                            <option value="dark" <?= old('txtTheme') == 'dark' ? 'selected' : '' ?>>Dark</option>
                            <option value="light" <?= old('txtTheme') == 'light' ? 'selected' : '' ?>>Light</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="bitActive" class="form-label">Active</label>
                        <select class="form-select" id="bitActive" name="bitActive">
                            <option value="1" <?= old('bitActive', 1) == 1 ? 'selected' : '' ?>>Yes</option>
                            <option value="0" <?= old('bitActive') == 0 ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="agreeTos" required>
                        <label class="form-check-label" for="agreeTos">
                            I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                        </label>
                    </div>
                </div>
                  <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Create Tenant</button>
                    <a href="<?= base_url('tenant') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            
            <script>
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
            </script>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
