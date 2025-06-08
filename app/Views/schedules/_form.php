<?php
$isEdit = isset($schedule);
?>

<?= csrf_field() ?>

<?php if (isset($tenants) && count($tenants) > 1) : ?>
<div class="mb-3">
    <label for="tenant_id" class="form-label">Tenant <span class="text-danger">*</span></label>
    <select class="form-select" id="intTenantID" name="intTenantID" required>
        <option value="" selected disabled>Select Tenant</option>
        <?php foreach ($tenants as $tenant) : ?>
            <option value="<?= $tenant['intTenantID'] ?>" <?= (old('tenant_id', $schedule['intTenantID'] ?? '') == $tenant['intTenantID'] || (isset($_GET['tenant_id']) && $_GET['tenant_id'] == $tenant['intTenantID'])) ? 'selected' : '' ?>>
                <?= esc($tenant['txtName']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<?php else: ?>
<input type="hidden" name="intTenantID" value="<?= $tenant_id ?? '' ?>">
<?php endif; ?>

<div class="mb-3">
    <label for="intServiceID" class="form-label">Service <span class="text-danger">*</span></label>
    <select class="form-select" id="intServiceID" name="intServiceID" required>
        <?php if (empty($services)) : ?>
            <option value="" selected disabled>Please select a tenant first</option>
        <?php else : ?>
            <option value="" selected disabled>Select Service</option>
            <?php foreach ($services as $service) : ?>
                <option value="<?= $service['intServiceID'] ?>" 
                        data-slot-duration="<?= $service['intDuration'] ?>"
                        <?= (old('intServiceID', $schedule['intServiceID'] ?? '') == $service['intServiceID'] || (isset($_GET['service_id']) && $_GET['service_id'] == $service['intServiceID'])) ? 'selected' : '' ?>>
                    <?= esc($service['txtName']) ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="txtDay" class="form-label">Day <span class="text-danger">*</span></label>
        <select class="form-select" id="txtDay" name="txtDay" required>
            <option value="" selected disabled>Select Day</option>
            <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) : ?>
                <option value="<?= $day ?>" <?= old('txtDay', $schedule['txtDay'] ?? '') == $day ? 'selected' : '' ?>><?= $day ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label for="bitIsAvailable" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" id="bitIsAvailable" name="bitIsAvailable" required>
            <option value="1" <?= old('bitIsAvailable', $schedule['bitIsAvailable'] ?? '1') == '1' ? 'selected' : '' ?>>Available</option>
            <option value="0" <?= old('bitIsAvailable', $schedule['bitIsAvailable'] ?? '') === '0' ? 'selected' : '' ?>>Not Available</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="dtmStartTime" class="form-label">Start Time <span class="text-danger">*</span></label>
        <input type="time" class="form-control" id="dtmStartTime" name="dtmStartTime" 
               value="<?= old('dtmStartTime', $schedule['dtmStartTime'] ?? '09:00') ?>" required>
    </div>
    <div class="col-md-6">
        <label for="dtmEndTime" class="form-label">End Time <span class="text-danger">*</span></label>
        <input type="time" class="form-control" id="dtmEndTime" name="dtmEndTime" 
               value="<?= old('dtmEndTime', $schedule['dtmEndTime'] ?? '17:00') ?>" required>
    </div>
</div>

<div class="mb-3">
    <label for="intSlotDuration" class="form-label">Slot Duration (minutes) <span class="text-danger">*</span></label>
    <input type="number" class="form-control" id="intSlotDuration" name="intSlotDuration" 
           value="<?= old('intSlotDuration', $schedule['intSlotDuration'] ?? '60') ?>" min="15" step="15" required>
    <small class="form-text text-muted">Minimum duration: 15 minutes</small>
</div>

<div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <a href="<?= base_url('schedules' . (isset($_GET['service_id']) ? '?service_id=' . $_GET['service_id'] : '')) ?>" class="btn btn-secondary me-md-2">
        Cancel
    </a>
    <button type="submit" class="btn btn-primary">
        <?= $isEdit ? 'Update' : 'Create' ?> Schedule
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize service slot duration
    const serviceSelect = document.getElementById('intServiceID');
    const slotDurationInput = document.getElementById('intSlotDuration');
    
    function updateSlotDuration() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.slotDuration) {
            slotDurationInput.value = selectedOption.dataset.slotDuration;
        }
    }
    
    serviceSelect.addEventListener('change', updateSlotDuration);
    
    // Set initial slot duration if service is preselected
    if (serviceSelect.value) {
        updateSlotDuration();
    }
    
    // Time validation
    const startTimeInput = document.getElementById('dtmStartTime');
    const endTimeInput = document.getElementById('dtmEndTime');
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        
        if (startTime >= endTime) {
            e.preventDefault();
            alert('End time must be later than start time');
        }
    });
});
