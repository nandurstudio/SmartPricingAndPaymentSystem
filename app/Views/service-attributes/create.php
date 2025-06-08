<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Service Attribute</h4>
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

                <form action="<?= base_url('service-attributes/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="intServiceTypeID" class="form-label">Service Type</label>
                        <select name="intServiceTypeID" id="intServiceTypeID" class="form-select" required>
                            <option value="">Select Service Type</option>
                            <?php foreach ($serviceTypes as $type): ?>
                                <option value="<?= $type['intServiceTypeID'] ?>">
                                    <?= esc($type['txtName']) ?> (<?= esc($type['txtCategory']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="txtName" class="form-label">Attribute Name</label>
                        <input type="text" class="form-control" id="txtName" name="txtName" 
                               value="<?= old('txtName') ?>" required>
                        <div class="form-text">Use lowercase letters and underscores (e.g., room_capacity)</div>
                    </div>

                    <div class="mb-3">
                        <label for="txtLabel" class="form-label">Display Label</label>
                        <input type="text" class="form-control" id="txtLabel" name="txtLabel" 
                               value="<?= old('txtLabel') ?>" required>
                        <div class="form-text">How the attribute will be displayed to users</div>
                    </div>

                    <div class="mb-3">
                        <label for="txtFieldType" class="form-label">Field Type</label>
                        <select name="txtFieldType" id="txtFieldType" class="form-select" required>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="boolean">Boolean</option>
                            <option value="select">Select</option>
                            <option value="date">Date</option>
                            <option value="time">Time</option>
                            <option value="datetime">Date & Time</option>
                        </select>
                    </div>

                    <div class="mb-3" id="optionsContainer" style="display: none;">
                        <label for="jsonOptions" class="form-label">Options (one per line)</label>
                        <textarea class="form-control" id="jsonOptions" name="jsonOptions" rows="4"><?= old('jsonOptions') ?></textarea>
                        <div class="form-text">For select fields, enter one option per line</div>
                    </div>

                    <div class="mb-3">
                        <label for="txtDefaultValue" class="form-label">Default Value</label>
                        <input type="text" class="form-control" id="txtDefaultValue" name="txtDefaultValue" 
                               value="<?= old('txtDefaultValue') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="txtValidation" class="form-label">Validation Rules</label>
                        <input type="text" class="form-control" id="txtValidation" name="txtValidation" 
                               value="<?= old('txtValidation') ?>">
                        <div class="form-text">e.g., min:1|max:100|required</div>
                    </div>

                    <div class="mb-3">
                        <label for="intDisplayOrder" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="intDisplayOrder" name="intDisplayOrder" 
                               value="<?= old('intDisplayOrder', 0) ?>">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="bitRequired" name="bitRequired" value="1"
                                   <?= old('bitRequired') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="bitRequired">Required Field</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <a href="<?= base_url('service-attributes') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Attribute</button>
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
    
    fieldTypeSelect.addEventListener('change', function() {
        if (this.value === 'select') {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    });
});
</script>
<?= $this->endSection() ?>
