<?php
$id = isset($service['intServiceID']) ? $service['intServiceID'] : '';
$name = isset($service['txtName']) ? $service['txtName'] : '';
$type_id = isset($service['intServiceTypeID']) ? $service['intServiceTypeID'] : '';
$price = isset($service['decPrice']) ? $service['decPrice'] : '';
$duration = isset($service['intDuration']) ? $service['intDuration'] : 60;
$capacity = isset($service['intCapacity']) ? $service['intCapacity'] : 1;
$description = isset($service['txtDescription']) ? $service['txtDescription'] : '';
$is_active = isset($service['bitActive']) ? $service['bitActive'] : 1;
?>

<?php if (isset($tenants) && count($tenants) > 0 && session()->get('roleID') == 1) : ?>
<div class="mb-3">
    <label for="intTenantID" class="form-label">Tenant <span class="text-danger">*</span></label>
    <select class="form-select <?= session('errors.intTenantID') ? 'is-invalid' : '' ?>" 
            id="intTenantID" name="intTenantID" required>
        <option value="">Select Tenant</option>
        <?php foreach ($tenants as $tenant) : ?>
            <option value="<?= $tenant['intTenantID'] ?>" <?= old('intTenantID', $service['intTenantID'] ?? '') == $tenant['intTenantID'] ? 'selected' : '' ?>>
                <?= esc($tenant['txtTenantName']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (session('errors.intTenantID')) : ?>
        <div class="invalid-feedback">
            <?= session('errors.intTenantID') ?>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="txtName" class="form-label">Service Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control <?= session('errors.txtName') ? 'is-invalid' : '' ?>" 
               id="txtName" name="txtName" value="<?= old('txtName', $name) ?>" required>
        <?php if (session('errors.txtName')) : ?>
            <div class="invalid-feedback">
                <?= session('errors.txtName') ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <label for="intServiceTypeID" class="form-label">Service Type <span class="text-danger">*</span></label>
        <select class="form-select <?= session('errors.intServiceTypeID') ? 'is-invalid' : '' ?>" 
                id="intServiceTypeID" name="intServiceTypeID" required>
            <option value="">Select Service Type</option>
            <?php foreach ($serviceTypes as $type) : ?>
                <option value="<?= $type['intServiceTypeID'] ?>" <?= old('intServiceTypeID', $type_id) == $type['intServiceTypeID'] ? 'selected' : '' ?>>
                    <?= esc($type['txtName']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (session('errors.intServiceTypeID')) : ?>
            <div class="invalid-feedback">
                <?= session('errors.intServiceTypeID') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="mb-3">
    <label for="txtDescription" class="form-label">Description <span class="text-danger">*</span></label>
    <textarea class="form-control <?= session('errors.txtDescription') ? 'is-invalid' : '' ?>" 
              id="txtDescription" name="txtDescription" rows="3" required><?= old('txtDescription', $description) ?></textarea>
    <?php if (session('errors.txtDescription')) : ?>
        <div class="invalid-feedback">
            <?= session('errors.txtDescription') ?>
        </div>
    <?php endif; ?>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label for="decPrice" class="form-label">Price <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" step="0.01" class="form-control <?= session('errors.decPrice') ? 'is-invalid' : '' ?>" 
                   id="decPrice" name="decPrice" value="<?= old('decPrice', $price) ?>" required>
            <?php if (session('errors.decPrice')) : ?>
                <div class="invalid-feedback">
                    <?= session('errors.decPrice') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-4">
        <label for="intDuration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
        <input type="number" class="form-control <?= session('errors.intDuration') ? 'is-invalid' : '' ?>" 
               id="intDuration" name="intDuration" value="<?= old('intDuration', $duration) ?>" required>
        <?php if (session('errors.intDuration')) : ?>
            <div class="invalid-feedback">
                <?= session('errors.intDuration') ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-4">
        <label for="intCapacity" class="form-label">Capacity</label>
        <input type="number" class="form-control <?= session('errors.intCapacity') ? 'is-invalid' : '' ?>" 
               id="intCapacity" name="intCapacity" value="<?= old('intCapacity', $capacity) ?>">
        <?php if (session('errors.intCapacity')) : ?>
            <div class="invalid-feedback">
                <?= session('errors.intCapacity') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="dynamic-fields" class="mb-3">
    <!-- Fields will be populated via JavaScript based on service type -->
</div>

<div class="mb-3">
    <label for="txtImage" class="form-label">Service Image</label>
    <div class="mb-2" id="image-preview-container">
        <?php if (!empty($service['txtImage'])) : ?>
            <img src="<?= base_url('uploads/services/' . $service['txtImage']) ?>"
                 alt="<?= esc($service['txtName']) ?>"
                 class="img-thumbnail"
                 id="image-preview"
                 style="max-height: 150px;">
        <?php else: ?>
            <img src="#" alt="Preview" class="img-thumbnail d-none" id="image-preview" style="max-height: 150px;">
        <?php endif; ?>
    </div>
    <?php if (!empty($service['txtImage'])) : ?>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
            <label class="form-check-label" for="remove_image">Remove current image</label>
        </div>
    <?php endif; ?>
    <input type="file" class="form-control <?= session('errors.txtImage') ? 'is-invalid' : '' ?>"
           id="txtImage" name="txtImage" accept="image/*">
    <div class="form-text">Upload a new image to update. (Max size: 2MB, Formats: JPG, PNG)</div>
    <?php if (session('errors.txtImage')) : ?>
        <div class="invalid-feedback">
            <?= session('errors.txtImage') ?>
        </div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="bitActiveToggle" class="form-label">Status</label>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="bitActiveToggle" name="bitActiveToggle" <?= old('bitActive', $is_active) == 1 ? 'checked' : '' ?>>
        <input type="hidden" id="bitActive" name="bitActive" value="<?= old('bitActive', $is_active) ?>">
        <label class="form-check-label ms-2" for="bitActiveToggle" id="statusLabel"></label>
    </div>
    <?php if (session('errors.bitActive')) : ?>
        <div class="invalid-feedback d-block">
            <?= session('errors.bitActive') ?>
        </div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <button type="submit" class="btn btn-primary">
        <?= $id ? 'Update Service' : 'Create Service' ?>
    </button>
    <a href="<?= base_url('services') ?>" class="btn btn-secondary">Cancel</a>
</div>

<!-- <script src="<?= base_url('assets/js/pages/service-form.js') ?>"></script> -->
