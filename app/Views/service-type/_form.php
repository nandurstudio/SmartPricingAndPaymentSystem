<?php
$isEdit = isset($serviceType['intServiceTypeID']);
$id = $serviceType['intServiceTypeID'] ?? '';
?>

<form action="<?= base_url('service-types/' . ($isEdit ? "update/$id" : 'store')) ?>" 
      method="POST" 
      class="needs-validation" 
      novalidate>
    
    <?= csrf_field() ?>

    <!-- Service Type Name -->
    <div class="mb-3">
        <label class="form-label" for="txtName">
            <i class="bi bi-tag-fill me-1 text-primary"></i>
            Service Type Name <span class="text-danger">*</span>
        </label>
        <input type="text" 
               class="form-control <?= session('errors.txtName') ? 'is-invalid' : '' ?>" 
               id="txtName" 
               name="txtName" 
               value="<?= old('txtName', $serviceType['txtName'] ?? '') ?>" 
               maxlength="255"
               placeholder="Enter service type name"
               required
               autofocus>
        <?php if (session('errors.txtName')): ?>
            <div class="invalid-feedback">
                <?= session('errors.txtName') ?>
            </div>
        <?php else: ?>
            <div class="form-text">
                Maximum 255 characters
            </div>
        <?php endif ?>
    </div>

    <!-- Category -->
    <div class="mb-3">
        <label class="form-label" for="txtCategory">
            <i class="bi bi-folder me-1 text-primary"></i>
            Category
        </label>
        <input type="text" 
               class="form-control <?= session('errors.txtCategory') ? 'is-invalid' : '' ?>" 
               id="txtCategory" 
               name="txtCategory" 
               value="<?= old('txtCategory', $serviceType['txtCategory'] ?? '') ?>" 
               maxlength="100"
               placeholder="Enter category (optional)">
        <?php if (session('errors.txtCategory')): ?>
            <div class="invalid-feedback">
                <?= session('errors.txtCategory') ?>
            </div>
        <?php endif ?>
        <div class="form-text">
            Optional category to group service types
        </div>
    </div>

    <!-- Icon -->
    <div class="mb-3">
        <label class="form-label" for="txtIcon">
            <i class="bi bi-puzzle me-1 text-primary"></i>
            Icon
        </label>
        <input type="text" 
               class="form-control <?= session('errors.txtIcon') ? 'is-invalid' : '' ?>" 
               id="txtIcon" 
               name="txtIcon" 
               value="<?= old('txtIcon', $serviceType['txtIcon'] ?? '') ?>" 
               maxlength="255"
               placeholder="Enter icon name (optional)">
        <?php if (session('errors.txtIcon')): ?>
            <div class="invalid-feedback">
                <?= session('errors.txtIcon') ?>
            </div>
        <?php endif ?>
        <div class="form-text">
            Optional Bootstrap icon name (e.g., "bi-gear" or "bi-tools")
        </div>
    </div>

    <!-- Description -->
    <div class="mb-3">
        <label class="form-label" for="txtDescription">
            <i class="bi bi-card-text me-1 text-primary"></i>
            Description
        </label>
        <textarea class="form-control <?= session('errors.txtDescription') ? 'is-invalid' : '' ?>" 
                  id="txtDescription" 
                  name="txtDescription" 
                  rows="3"
                  placeholder="Enter description (optional)"><?= old('txtDescription', $serviceType['txtDescription'] ?? '') ?></textarea>
        <?php if (session('errors.txtDescription')): ?>
            <div class="invalid-feedback">
                <?= session('errors.txtDescription') ?>
            </div>
        <?php endif ?>
        <div class="form-text">
            A detailed description of this service type
        </div>
    </div>

    <!-- Status -->
    <div class="mb-4">
        <label class="form-label d-block">
            <i class="bi bi-toggle2-on me-1 text-primary"></i>
            Status
        </label>
        <div class="form-check form-switch">
            <input type="checkbox" 
                   class="form-check-input" 
                   id="bitActive" 
                   name="bitActive" 
                   value="1" 
                   <?= old('bitActive', $serviceType['bitActive'] ?? '1') == '1' ? 'checked' : '' ?>>
            <label class="form-check-label" for="bitActive">
                Enable this service type
                <span id="statusLabel" class="badge bg-success ms-2">Active</span>
            </label>
        </div>
        <div class="form-text">
            Active service types can be assigned to services
        </div>
    </div>

    <!-- Form Actions -->
    <div class="d-flex justify-content-end gap-2">
        <a href="<?= base_url('service-types') ?>" class="btn btn-light" data-bs-toggle="tooltip" title="Return to service types list">
            <i class="bi bi-x-circle me-1"></i> Cancel
        </a>
        <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="<?= $isEdit ? 'Save changes to service type' : 'Create new service type' ?>">
            <i class="bi bi-save me-1"></i> <?= $isEdit ? 'Update' : 'Create' ?> Service Type
        </button>
    </div>
</form>
