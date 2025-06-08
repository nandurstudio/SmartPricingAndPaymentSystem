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

<div class="form-group mb-3">    <label for="txtName" class="form-label">Service Name</label>
    <input type="text" class="form-control <?= session('errors.txtName') ? 'is-invalid' : '' ?>" 
           id="txtName" name="txtName" value="<?= old('txtName', $name) ?>" required>
    <?php if (session('errors.txtName')) : ?>
        <div class="invalid-feedback"><?= session('errors.txtName') ?></div>
    <?php endif; ?>
</div>

<div class="form-group mb-3">    <label for="intServiceTypeID" class="form-label">Service Type</label>
    <select class="form-control <?= session('errors.intServiceTypeID') ? 'is-invalid' : '' ?>" 
            id="intServiceTypeID" name="intServiceTypeID" required>
        <option value="">Select Service Type</option>
        <?php foreach ($serviceTypes as $type): ?>
            <option value="<?= $type['intServiceTypeID'] ?>" <?= old('intServiceTypeID', $type_id) == $type['intServiceTypeID'] ? 'selected' : '' ?>>
                <?= esc($type['txtName']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (session('errors.intServiceTypeID')) : ?>
        <div class="invalid-feedback"><?= session('errors.intServiceTypeID') ?></div>
    <?php endif; ?>
</div>

<div class="form-group mb-3">    <label for="decPrice" class="form-label">Price</label>
    <div class="input-group">
        <span class="input-group-text">Rp</span>
        <input type="number" class="form-control <?= session('errors.decPrice') ? 'is-invalid' : '' ?>" 
               id="decPrice" name="decPrice" value="<?= old('decPrice', $price) ?>" required min="0" step="0.01">
    </div>
    <?php if (session('errors.decPrice')) : ?>
        <div class="invalid-feedback"><?= session('errors.decPrice') ?></div>
    <?php endif; ?>
</div>

<div class="form-group mb-3">    <label for="intDuration" class="form-label">Duration (minutes)</label>
    <input type="number" class="form-control <?= session('errors.intDuration') ? 'is-invalid' : '' ?>" 
           id="intDuration" name="intDuration" value="<?= old('intDuration', $duration) ?>" required min="1">
    <?php if (session('errors.intDuration')) : ?>
        <div class="invalid-feedback"><?= session('errors.intDuration') ?></div>
    <?php endif; ?>
</div>

<div class="form-group mb-3">    <label for="intCapacity" class="form-label">Capacity (people)</label>
    <input type="number" class="form-control <?= session('errors.intCapacity') ? 'is-invalid' : '' ?>" 
           id="intCapacity" name="intCapacity" value="<?= old('intCapacity', $capacity) ?>" required min="1">
    <?php if (session('errors.intCapacity')) : ?>
        <div class="invalid-feedback"><?= session('errors.intCapacity') ?></div>
    <?php endif; ?>
</div>

<div class="form-group mb-3">    <label for="txtDescription" class="form-label">Description</label>
    <textarea class="form-control <?= session('errors.txtDescription') ? 'is-invalid' : '' ?>" 
              id="txtDescription" name="txtDescription" rows="3" required><?= old('txtDescription', $description) ?></textarea>
    <?php if (session('errors.txtDescription')) : ?>
        <div class="invalid-feedback"><?= session('errors.txtDescription') ?></div>
    <?php endif; ?>
</div>

<div class="form-check mb-3">    <input type="checkbox" class="form-check-input" id="bitActive" name="bitActive" value="1" 
           <?= old('bitActive', $is_active) ? 'checked' : '' ?>>
    <label class="form-check-label" for="bitActive">Active</label>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="<?= base_url('services') ?>" class="btn btn-secondary">Cancel</a>
