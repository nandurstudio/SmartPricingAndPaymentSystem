<?php
// Determine if this is edit mode
$isEdit = isset($schedule);
?>

<?= csrf_field() ?>

<?php // Only show Service and Day dropdowns in CREATE mode ?>
<?php if (!$isEdit): ?>
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
    <div class="row mb-3">
        <div class="col-md-6">
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
        <div class="col-md-6">
            <label for="txtDay" class="form-label">Day <span class="text-danger">*</span></label>
            <select class="form-select" id="txtDay" name="txtDay" required>
                <option value="" selected disabled>Select Day</option>
                <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) : ?>
                    <option value="<?= $day ?>" <?= old('txtDay', $schedule['txtDay'] ?? '') == $day ? 'selected' : '' ?>><?= $day ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php else: ?>
    <?php // In EDIT mode, Service and Day are readonly and submitted via hidden input ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Service</label>
            <input type="text" class="form-control bg-light text-secondary" value="<?= esc($schedule['txtServiceName'] ?? 'Service not found') ?>" readonly disabled>
            <input type="hidden" name="intServiceID" value="<?= $schedule['intServiceID'] ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Day</label>
            <input type="text" class="form-control bg-light text-secondary" value="<?= esc($schedule['txtDay']) ?>" readonly disabled>
            <input type="hidden" name="txtDay" value="<?= $schedule['txtDay'] ?>">
        </div>
    </div>
<?php endif; ?>

<div class="row mb-3">
    <div class="col-md-6 col-12 mb-2 mb-md-0">
        <label for="bitIsAvailable" class="form-label">Availability <span class="text-danger">*</span></label><br>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="bitIsAvailable" name="bitIsAvailable" value="1" <?= old('bitIsAvailable', $schedule['bitIsAvailable'] ?? '1') == '1' ? 'checked' : '' ?>>
            <input type="hidden" name="bitIsAvailableHidden" value="0">
            <label class="form-check-label" for="bitIsAvailable">
                <span id="avail-label"><?= old('bitIsAvailable', $schedule['bitIsAvailable'] ?? '1') == '1' ? 'Available' : 'Not Available' ?></span>
            </label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6 col-12 mb-2 mb-md-0">
        <label for="dtmStartTime" class="form-label">Start Time <span class="text-danger">*</span></label>
        <input type="time" class="form-control time-cursor" id="dtmStartTime" name="dtmStartTime" 
               value="<?= old('dtmStartTime', $schedule['dtmStartTime'] ?? '09:00') ?>" required onclick="this.showPicker && this.showPicker()">
        <small class="form-text text-muted">Jam mulai jadwal layanan pada hari ini.</small>
    </div>
    <div class="col-md-6 col-12">
        <label for="dtmEndTime" class="form-label">End Time <span class="text-danger">*</span></label>
        <input type="time" class="form-control time-cursor" id="dtmEndTime" name="dtmEndTime" 
               value="<?= old('dtmEndTime', $schedule['dtmEndTime'] ?? '17:00') ?>" required onclick="this.showPicker && this.showPicker()">
        <small class="form-text text-muted">Jam selesai jadwal layanan pada hari ini.</small>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6 col-12 mb-2 mb-md-0">
        <label for="intSlotDuration" class="form-label">Slot Duration (minutes) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="intSlotDuration" name="intSlotDuration" 
               value="<?= old('intSlotDuration', $schedule['intSlotDuration'] ?? '60') ?>" min="15" step="15" required>
        <small class="form-text text-muted">Durasi tiap slot booking dalam menit. Contoh: 60 = 1 jam per slot.</small>
    </div>
    <div class="col-md-6 col-12 d-flex align-items-end">
        <div class="w-100">
            <label class="form-label">Slot Information</label>
            <div class="alert alert-info py-2 px-3 mb-0" id="slot-info" style="font-size:0.95em;">
                Akan dibuat <span id="slot-count" class="fw-bold">0</span> slot.<br>
                Slot pertama: <span id="first-slot" class="fw-bold">-</span><br>
                Slot terakhir: <span id="last-slot" class="fw-bold">-</span>
            </div>
        </div>
    </div>
</div>

<style>
    .time-cursor { cursor: pointer; }
    input[readonly], input[disabled], select[readonly], select[disabled] {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
        cursor: not-allowed;
    }
</style>

<!-- JavaScript functionality moved to schedules.js and uses jQuery -->
