<!-- app/Views/role_menu_access/create.php -->
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('role_menu_access') ?>">Role Menu Access</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-pencil-square me-1"></i>
            Edit Role Menu Access
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Please check the form below for errors.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>

            <form action="<?= base_url("role_menu_access/update/{$roleMenuAccess['intRoleMenuID']}") ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select name="intRoleID" id="role_id" class="form-control" required>
                        <option value="">Select a Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['intRoleID'] ?>" <?= $role['intRoleID'] == $roleMenuAccess['intRoleID'] ? 'selected' : '' ?>>
                                <?= esc($role['txtRoleName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="menu_id" class="form-label">Menu</label>
                    <select name="intMenuID" id="menu_id" class="form-control" required>
                        <option value="">Select a Menu</option>
                        <?php foreach ($menus as $menu): ?>
                            <option value="<?= $menu['intMenuID'] ?>" <?= $menu['intMenuID'] == $roleMenuAccess['intMenuID'] ? 'selected' : '' ?>>
                                <?= esc($menu['txtMenuName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="bitCanView" id="can_view" class="form-check-input" value="1" 
                               <?= $roleMenuAccess['bitCanView'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="can_view">Can View</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="bitCanAdd" id="can_add" class="form-check-input" value="1"
                               <?= $roleMenuAccess['bitCanAdd'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="can_add">Can Add</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="bitCanEdit" id="can_edit" class="form-check-input" value="1"
                               <?= $roleMenuAccess['bitCanEdit'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="can_edit">Can Edit</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="bitCanDelete" id="can_delete" class="form-check-input" value="1"
                               <?= $roleMenuAccess['bitCanDelete'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="can_delete">Can Delete</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Update Access
                    </button>
                    <a href="<?= base_url('role_menu_access') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>