<?php
// This partial is used for both create and edit special schedule modals.
// Variables expected: $services, $serviceNotFound, $isEdit (bool), $special (array|null)
?>
<div class="mb-3">
    <label for="special_service_id" class="form-label">Service <span class="text-danger">*</span></label>
    <?php if (!empty($isEdit)) : ?>
        <input type="text" class="form-control" id="special_service_name" value="<?= esc($special['service_name'] ?? '') ?>" readonly>
        <input type="hidden" id="special_service_id" name="intServiceID" value="<?= esc($special['service_id'] ?? '') ?>">
    <?php else : ?>
        <select class="form-select" id="special_service_id" name="intServiceID" required <?= (isset($services) && count($services) === 1) ? 'disabled' : '' ?>>
            <?php if (isset($serviceNotFound) && $serviceNotFound): ?>
                <option value="" selected disabled>Service not found</option>
            <?php elseif (isset($services) && count($services) > 0): ?>
                <option value="" disabled>Select Service</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= $service['id'] ?>" <?= ((isset($_GET['service_id']) && $_GET['service_id'] == $service['id']) || (count($services) === 1)) ? 'selected' : '' ?>>
                        <?= esc($service['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="" selected disabled>No service available</option>
            <?php endif; ?>
        </select>
        <input type="hidden" id="hidden_special_service_id" name="intServiceID" value="<?php if (isset($services) && count($services) === 1) { echo $services[0]['id']; } ?>">
        <?php if (isset($services) && count($services) === 1): ?>
            <input type="hidden" name="intServiceID" value="<?= $services[0]['id'] ?>">
        <?php endif; ?>
    <?php endif; ?>
</div>
<div class="mb-3">
    <label for="special_date" class="form-label">Date <span class="text-danger">*</span></label>
    <input type="date" class="form-control" id="special_date" name="dtmSpecialDate" min="<?= date('Y-m-d') ?>" required value="<?= esc($special['date'] ?? '') ?>">
</div>
<div class="mb-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="1" id="<?= !empty($isEdit) ? 'edit_is_closed' : 'is_closed' ?>" name="bitIsClosed" <?= !empty($special['is_closed']) ? 'checked' : '' ?> >
        <label class="form-check-label" for="<?= !empty($isEdit) ? 'edit_is_closed' : 'is_closed' ?>">
            Closed on this date
        </label>
    </div>
</div>
<div id="<?= !empty($isEdit) ? 'edit-operating-hours-div' : 'operating-hours-div' ?>" class="times-container">
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="special_start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
            <input type="time" class="form-control" id="<?= !empty($isEdit) ? 'edit_special_start_time' : 'special_start_time' ?>" name="dtmStartTime" value="<?= esc($special['start_time'] ?? '09:00') ?>">
        </div>
        <div class="col-md-6">
            <label for="special_end_time" class="form-label">End Time <span class="text-danger">*</span></label>
            <input type="time" class="form-control" id="<?= !empty($isEdit) ? 'edit_special_end_time' : 'special_end_time' ?>" name="dtmEndTime" value="<?= esc($special['end_time'] ?? '17:00') ?>">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <label for="special_slot_duration" class="form-label">Slot Duration (minutes)</label>
            <input type="number" class="form-control" id="special_slot_duration" name="intSlotDuration" value="<?= esc($special['intSlotDuration'] ?? '60') ?>" min="1">
        </div>
    </div>
    <div id="<?= !empty($isEdit) ? 'edit-closed-all-day-label' : 'closed-all-day-label' ?>" class="text-muted" style="display:none;">
        <i class="bi bi-moon"></i> Closed All Day
    </div>
</div>
<div class="mb-3">
    <label for="special_notes" class="form-label">Reason/Notes</label>
    <textarea class="form-control" id="special_notes" name="txtNote" rows="3"><?= esc($special['notes'] ?? '') ?></textarea>
</div>
