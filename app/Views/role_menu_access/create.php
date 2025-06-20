<!-- app/Views/role_menu_access/create.php -->
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('role-menu-access') ?>">Role Menu Access</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Create New Role Menu Access
        </div>
        <div class="card-body">            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Please check the form below for errors.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>

            <form action="<?= base_url('role-menu-access/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select name="intRoleID" id="role_id" class="form-control" required>
                        <option value="">Select a Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['intRoleID'] ?>"><?= esc($role['txtRoleName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="menu_id" class="form-label">Menu</label>
                    <select name="intMenuID" id="menu_id" class="form-control" required>
                        <option value="">Select a Menu</option>
                        <?php foreach ($menus as $menu): ?>
                            <option value="<?= $menu['intMenuID'] ?>" <?= ($selectedMenuId == $menu['intMenuID'] ? 'selected' : '') ?>>
                                <?= esc($menu['txtMenuName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="bitActive" id="bitActive" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="bitActive">Active</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Access
                    </button>
                    <a href="<?= base_url('role-menu-access') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>