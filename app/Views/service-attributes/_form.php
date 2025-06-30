<?php if (!isset($form_action)) $form_action = ''; ?>
<!-- <?php if (empty($form_action)) echo 'Warning: form_action URL is not set!'; ?> -->
<?php if (empty($form_action)): ?>
<div class="alert alert-danger">Form action URL is not set! Silakan hubungi admin/developer.</div>
<?php endif; ?>
<form action="<?= $form_action ?>" method="post" autocomplete="off" id="attributeForm" onsubmit="return validateForm(event)">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label for="intServiceTypeID" class="form-label">Service Type <span class="text-danger">*</span></label>
        <select name="intServiceTypeID" id="intServiceTypeID" class="form-select" required>
            <option value="">Select Service Type</option>
            <?php foreach ($serviceTypes as $type): ?>
                <option value="<?= $type['intServiceTypeID'] ?>" <?= (isset($attribute) && $attribute->intServiceTypeID == $type['intServiceTypeID']) || old('intServiceTypeID') == $type['intServiceTypeID'] ? 'selected' : '' ?>>
                    <?= esc($type['txtName']) ?> (<?= esc($type['txtCategory']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <div class="form-text">Choose the service type this attribute belongs to.</div>
    </div>
    <div class="mb-3">
        <label for="txtName" class="form-label">Attribute Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="txtName" name="txtName" value="<?= old('txtName', $attribute->txtName ?? '') ?>" required maxlength="100" pattern="[a-z0-9_]+">
        <div class="form-text">Use lowercase letters, numbers, and underscores only (e.g., <code>room_capacity</code>).</div>
    </div>
    <div class="mb-3">
        <label for="txtLabel" class="form-label">Display Label <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="txtLabel" name="txtLabel" value="<?= old('txtLabel', $attribute->txtLabel ?? '') ?>" required maxlength="255">
        <div class="form-text">How this attribute will be shown to users (e.g., <code>Room Capacity</code>).</div>
    </div>
    <div class="mb-3">
        <label for="txtFieldType" class="form-label">Field Type <span class="text-danger">*</span></label>
        <select name="txtFieldType" id="txtFieldType" class="form-select" required>
            <option value="text" <?= (old('txtFieldType', $attribute->txtFieldType ?? '') === 'text') ? 'selected' : '' ?>>Text</option>
            <option value="number" <?= (old('txtFieldType', $attribute->txtFieldType ?? '') === 'number') ? 'selected' : '' ?>>Number</option>
            <option value="boolean" <?= (old('txtFieldType', $attribute->txtFieldType ?? '') === 'boolean') ? 'selected' : '' ?>>Boolean</option>
            <option value="select" <?= (old('txtFieldType', $attribute->txtFieldType ?? '') === 'select') ? 'selected' : '' ?>>Select</option>
            <option value="date" <?= (old('txtFieldType', $attribute->txtFieldType ?? '') === 'date') ? 'selected' : '' ?>>Date</option>
            <option value="time" <?= (old('txtFieldType', $attribute->txtFieldType ?? '') === 'time') ? 'selected' : '' ?>>Time</option>
            <option value="datetime" <?= (old('txtFieldType', $attribute->txtFieldType ?? '') === 'datetime') ? 'selected' : '' ?>>Date & Time</option>
        </select>
        <div class="form-text">Choose the type of input for this attribute.</div>
    </div>
    <div class="mb-3" id="optionsContainer" style="display: none;">
        <label for="jsonOptions" class="form-label">Options (one per line)</label>
        <textarea class="form-control" id="jsonOptions" name="jsonOptions" rows="4" maxlength="1000"><?= old('jsonOptions', $attribute->jsonOptions ?? '') ?></textarea>
        <div class="form-text">For <b>Select</b> fields, enter one option per line (e.g., <code>Small</code>\n<code>Medium</code>\n<code>Large</code>).</div>
    </div>
    <div class="mb-3">
        <label for="txtDefaultValue" class="form-label">Default Value</label>
        <input type="text" class="form-control" id="txtDefaultValue" name="txtDefaultValue" value="<?= old('txtDefaultValue', $attribute->txtDefaultValue ?? '') ?>" maxlength="255">
        <div class="form-text">Optional. The value that will be pre-filled if not set by user.</div>
    </div>
    <div class="mb-3">
        <label for="txtValidation" class="form-label">Validation Rules</label>
        <input type="text" class="form-control" id="txtValidation" name="txtValidation" value="<?= old('txtValidation', $attribute->txtValidation ?? '') ?>" maxlength="1000">
        <div class="form-text">e.g., <code>min:1|max:100|required</code>. Separate rules with <code>|</code>.</div>
    </div>
    <div class="mb-3">
        <label for="intDisplayOrder" class="form-label">Display Order</label>
        <input type="number" class="form-control" id="intDisplayOrder" name="intDisplayOrder" value="<?= old('intDisplayOrder', $attribute->intDisplayOrder ?? 0) ?>" min="0" max="999">
        <div class="form-text">Order in which this attribute appears in forms (lower = first).</div>
    </div>
    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="bitRequired" name="bitRequired" value="1" <?= old('bitRequired', $attribute->bitRequired ?? false) ? 'checked' : '' ?>>
            <label class="form-check-label" for="bitRequired">Required Field</label>
        </div>
    </div>
    <div class="mb-3 d-flex gap-2">
        <a href="<?= base_url('service-attributes') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Cancel</a>
        <button type="submit" class="btn btn-primary"<?= empty($form_action) ? ' disabled' : '' ?>><i class="bi bi-save me-1"></i> Save Attribute</button>
    </div>
</form>
<script>
function validateForm(event) {
    console.log('Form action:', document.getElementById('attributeForm').action);
    console.log('Form method:', document.getElementById('attributeForm').method);
    
    // You can add custom validation here if needed
    return true; // Allow form submission
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('attributeForm');
    console.log('Initial form action:', form.action);
    
    const fieldTypeSelect = document.getElementById('txtFieldType');
    const optionsContainer = document.getElementById('optionsContainer');
    
    function toggleOptionsContainer() {
        if (fieldTypeSelect.value === 'select') {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    }
    
    fieldTypeSelect.addEventListener('change', toggleOptionsContainer);
    toggleOptionsContainer(); // Run on page load
});
</script>
