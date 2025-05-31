<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('service') ?>">Services</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
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
            <?php endif; ?>

            <form action="<?= base_url('service/update/' . $service['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <?php if (isset($tenants) && count($tenants) > 0 && session()->get('roleID') == 1) : ?>
                <div class="mb-3">
                    <label for="tenant_id" class="form-label">Tenant <span class="text-danger">*</span></label>
                    <select class="form-select" id="tenant_id" name="tenant_id" required>
                        <option value="" disabled>Select Tenant</option>
                        <?php foreach ($tenants as $tenant) : ?>
                            <option value="<?= $tenant['id'] ?>" <?= (old('tenant_id') ?? $service['tenant_id']) == $tenant['id'] ? 'selected' : '' ?>>
                                <?= esc($tenant['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else: ?>
                <input type="hidden" name="tenant_id" value="<?= $service['tenant_id'] ?>">
                <?php endif; ?>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?? $service['name'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="service_type_id" class="form-label">Service Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="service_type_id" name="service_type_id" required>
                            <option value="" disabled>Select Service Type</option>
                            <?php foreach ($serviceTypes as $type) : ?>
                                <option value="<?= $type['id'] ?>" data-fields='<?= json_encode($type['default_attributes'] ?? []) ?>' <?= (old('service_type_id') ?? $service['service_type_id']) == $type['id'] ? 'selected' : '' ?>>
                                    <?= esc($type['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= old('description') ?? $service['description'] ?></textarea>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="price" name="price" value="<?= old('price') ?? $service['price'] ?>" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration" name="duration" value="<?= old('duration') ?? $service['duration'] ?>" min="5" required>
                    </div>
                    <div class="col-md-4">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" value="<?= old('capacity') ?? $service['capacity'] ?? 1 ?>" min="1">
                    </div>
                </div>
                
                <!-- Dynamic fields for service type specific attributes -->
                <div id="dynamic-fields" class="mb-3">
                    <!-- Fields will be populated via JavaScript based on service type -->
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Service Image</label>
                    <?php if (!empty($service['image'])) : ?>
                        <div class="mb-2">
                            <img src="<?= base_url('uploads/services/' . $service['image']) ?>" alt="<?= esc($service['name']) ?>" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                            <label class="form-check-label" for="remove_image">
                                Remove current image
                            </label>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="form-text">Upload a new image to update. (Max size: 2MB, Formats: JPG, PNG)</div>
                </div>
                
                <div class="mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1" <?= (old('is_active') ?? $service['is_active']) == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= (old('is_active') ?? $service['is_active']) == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Service</button>
                    <a href="<?= base_url('service') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceTypeSelect = document.getElementById('service_type_id');
    const dynamicFieldsContainer = document.getElementById('dynamic-fields');
    
    // Current attribute values from service
    const currentAttributes = <?= json_encode($attributes ?? []) ?>;
    
    // Function to create dynamic fields based on service type attributes
    function createDynamicFields() {
        const selectedOption = serviceTypeSelect.options[serviceTypeSelect.selectedIndex];
        if (!selectedOption || selectedOption.value === '') {
            dynamicFieldsContainer.innerHTML = '';
            return;
        }
        
        let fields;
        try {
            fields = JSON.parse(selectedOption.dataset.fields);
        } catch (e) {
            fields = [];
        }
        
        if (!fields || fields.length === 0) {
            dynamicFieldsContainer.innerHTML = '';
            return;
        }
        
        let html = '<div class="card mb-3"><div class="card-header">Service Type Specific Information</div><div class="card-body">';
        
        fields.forEach(field => {
            const fieldId = `attr_${field.name.replace(/\s+/g, '_').toLowerCase()}`;
            const fieldValue = currentAttributes[field.name] || '';
            
            html += '<div class="mb-3">';
            html += `<label for="${fieldId}" class="form-label">${field.label || field.name}${field.required ? ' <span class="text-danger">*</span>' : ''}</label>`;
            
            // Different input types
            if (field.type === 'textarea') {
                html += `<textarea class="form-control" id="${fieldId}" name="attributes[${field.name}]" rows="3" ${field.required ? 'required' : ''}>${fieldValue}</textarea>`;
            } else if (field.type === 'select' && field.options) {
                html += `<select class="form-select" id="${fieldId}" name="attributes[${field.name}]" ${field.required ? 'required' : ''}>`;
                html += '<option value="" disabled>Select an option</option>';
                field.options.forEach(option => {
                    const optionValue = option.value || option;
                    const optionLabel = option.label || option;
                    const selected = fieldValue === optionValue ? 'selected' : '';
                    html += `<option value="${optionValue}" ${selected}>${optionLabel}</option>`;
                });
                html += '</select>';
            } else if (field.type === 'checkbox') {
                const checked = fieldValue == 1 ? 'checked' : '';
                html += '<div class="form-check">';
                html += `<input class="form-check-input" type="checkbox" id="${fieldId}" name="attributes[${field.name}]" value="1" ${checked}>`;
                html += `<label class="form-check-label" for="${fieldId}">${field.checkboxLabel || 'Yes'}</label>`;
                html += '</div>';
            } else {
                // Default to text input
                html += `<input type="${field.type || 'text'}" class="form-control" id="${fieldId}" name="attributes[${field.name}]" value="${fieldValue}" ${field.required ? 'required' : ''}>`;
            }
            
            if (field.hint) {
                html += `<div class="form-text">${field.hint}</div>`;
            }
            
            html += '</div>';
        });
        
        html += '</div></div>';
        dynamicFieldsContainer.innerHTML = html;
    }
    
    // Create fields on page load and when service type changes
    serviceTypeSelect.addEventListener('change', createDynamicFields);
    createDynamicFields();
});
</script>
<?= $this->endSection() ?>
