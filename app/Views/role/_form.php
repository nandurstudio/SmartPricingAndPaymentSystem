<?= csrf_field() ?>

<div class="mb-3">
    <label for="txtRoleName" class="form-label">Role Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control <?= session('errors.txtRoleName') ? 'is-invalid' : '' ?>" 
           id="txtRoleName" name="txtRoleName" 
           value="<?= set_value('txtRoleName', $role['txtRoleName'] ?? '') ?>" required>
    <?php if (session('errors.txtRoleName')): ?>
        <div class="invalid-feedback"><?= session('errors.txtRoleName') ?></div>
    <?php endif ?>
    <small class="text-muted">Enter a unique name for this role</small>
</div>

<div class="mb-3">
    <label for="txtRoleDesc" class="form-label">Description <span class="text-danger">*</span></label>
    <textarea class="form-control <?= session('errors.txtRoleDesc') ? 'is-invalid' : '' ?>"
              id="txtRoleDesc" name="txtRoleDesc" rows="3"><?= set_value('txtRoleDesc', $role['txtRoleDesc'] ?? '') ?></textarea>
    <?php if (session('errors.txtRoleDesc')): ?>
        <div class="invalid-feedback"><?= session('errors.txtRoleDesc') ?></div>
    <?php endif ?>
    <small class="text-muted">Provide a detailed description of this role's responsibilities</small>
</div>

<div class="mb-3">
    <label for="txtRoleNote" class="form-label">Additional Notes</label>
    <textarea class="form-control <?= session('errors.txtRoleNote') ? 'is-invalid' : '' ?>"
              id="txtRoleNote" name="txtRoleNote" rows="2"><?= set_value('txtRoleNote', $role['txtRoleNote'] ?? '') ?></textarea>
    <?php if (session('errors.txtRoleNote')): ?>
        <div class="invalid-feedback"><?= session('errors.txtRoleNote') ?></div>
    <?php endif ?>
    <small class="text-muted">Optional notes about this role (e.g. special permissions, restrictions)</small>
</div>

<div class="mb-3">
    <div class="form-check form-switch">
        <input type="hidden" name="bitStatus" value="0">
        <input type="checkbox" class="form-check-input" id="bitStatus" name="bitStatus" value="1" 
               <?= set_checkbox('bitStatus', '1', isset($role['bitStatus']) ? $role['bitStatus'] : true) ?>>
        <label class="form-check-label" for="bitStatus">Active</label>
    </div>
    <small class="text-muted">Inactive roles cannot be assigned to users</small>
</div>

<hr>

<div class="d-flex justify-content-end gap-2">
    <a href="<?= base_url('roles') ?>" class="btn btn-secondary">
        <i data-feather="x"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary">
        <i data-feather="<?= isset($role['intRoleID']) ? 'save' : 'plus' ?>"></i>
        <?= isset($role['intRoleID']) ? 'Update' : 'Create' ?> Role
    </button>
</div>
