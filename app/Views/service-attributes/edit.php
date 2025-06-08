<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Service Attribute</h4>
            </div>
            <div class="card-body">
                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('service-attributes/update/' . $attribute->intAttributeID) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="intServiceTypeID" class="form-label">Service Type</label>
                        <select name="intServiceTypeID" id="intServiceTypeID" class="form-select" required>
                            <option value="">Select Service Type</option>
                            <?php foreach ($serviceTypes as $type): ?>
                                <option value="<?= $type['intServiceTypeID'] ?>" 
                                        <?= $attribute->intServiceTypeID == $type['intServiceTypeID'] ? 'selected' : '' ?>>
                                    <?= esc($type['txtName']) ?> (<?= esc($type['txtCategory']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="txtName" class="form-label">Attribute Name</label>
                        <input type="text" class="form-control" id="txtName" name="txtName" 
                               value="<?= old('txtName', $attribute->txtName) ?>" required>
                        <div class="form-text">Use lowercase letters and underscores (e.g., room_capacity)</div>
                    </div>

                    <div class="mb-3">
                        <label for="txtLabel" class="form-label">Display Label</label>
                        <input type="text" class="form-control" id="txtLabel" name="txtLabel" 
                               value="<?= old('txtLabel', $attribute->txtLabel) ?>" required>
                        <div class="form-text">How the attribute will be displayed to users</div>
                    </div>

                    <div class="mb-3">
                        <label for="txtFieldType" class="form-label">Field Type</label>
                        <select name="txtFieldType" id="txtFieldType" class="form-select" required>
                            <option value="text" <?= $attribute->txtFieldType === 'text' ? 'selected' : '' ?>>Text</option>
                            <option value="number" <?= $attribute->txtFieldType === 'number' ? 'selected' : '' ?>>Number</option>
                            <option value="boolean" <?= $attribute->txtFieldType === 'boolean' ? 'selected' : '' ?>>Boolean</option>
                            <option value="select" <?= $attribute->txtFieldType === 'select' ? 'selected' : '' ?>>Select</option>
                            <option value="date" <?= $attribute->txtFieldType === 'date' ? 'selected' : '' ?>>Date</option>
                            <option value="time" <?= $attribute->txtFieldType === 'time' ? 'selected' : '' ?>>Time</option>
                            <option value="datetime" <?= $attribute->txtFieldType === 'datetime' ? 'selected' : '' ?>>Date & Time</option>
                        </select>
                    </div>

                    <div class="mb-3" id="optionsContainer" style="display: none;">
                        <label for="jsonOptions" class="form-label">Options (one per line)</label>
                        <textarea class="form-control" id="jsonOptions" name="jsonOptions" rows="4"><?= old('jsonOptions', $attribute->jsonOptions) ?></textarea>
                        <div class="form-text">For select fields, enter one option per line</div>
                    </div>

                    <div class="mb-3">
                        <label for="txtDefaultValue" class="form-label">Default Value</label>
                        <input type="text" class="form-control" id="txtDefaultValue" name="txtDefaultValue" 
                               value="<?= old('txtDefaultValue', $attribute->txtDefaultValue) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="txtValidation" class="form-label">Validation Rules</label>
                        <input type="text" class="form-control" id="txtValidation" name="txtValidation" 
                               value="<?= old('txtValidation', $attribute->txtValidation) ?>">
                        <div class="form-text">e.g., min:1|max:100|required</div>
                    </div>

                    <div class="mb-3">
                        <label for="intDisplayOrder" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="intDisplayOrder" name="intDisplayOrder" 
                               value="<?= old('intDisplayOrder', $attribute->intDisplayOrder) ?>">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="bitRequired" name="bitRequired" value="1"
                                   <?= $attribute->bitRequired ? 'checked' : '' ?>>
                            <label class="form-check-label" for="bitRequired">Required Field</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <a href="<?= base_url('service-attributes') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Attribute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
<?= $this->endSection() ?>
