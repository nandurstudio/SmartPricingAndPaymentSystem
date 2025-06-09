<?= $this->extend('layouts/main') ?>

<?= $this->section('themes') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/themes/default.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('menu') ?>">Menu Management</a></li>
        <li class="breadcrumb-item active">Edit Menu</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Menu: <?= esc($menu['txtMenuName']) ?>
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Please check the form below for errors.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>

            <form action="<?= base_url('menu/edit/' . $menu['intMenuID']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <div class="mb-3">
                    <label for="txtMenuName" class="form-label">Menu Name</label>
                    <input type="text" name="txtMenuName" value="<?= esc($menu['txtMenuName']) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="txtMenuLink" class="form-label">Menu Link</label>
                    <input type="text" name="txtMenuLink" value="<?= esc($menu['txtMenuLink']) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="txtIcon" class="form-label">Icon (CSS Class)</label>
                    <input type="text" name="txtIcon" value="<?= esc($menu['txtIcon']) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="intParentID" class="form-label">Parent ID</label>
                    <input type="number" name="intParentID" value="<?= esc($menu['intParentID']) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="intSortOrder" class="form-label">Sort Order</label>
                    <input type="number" name="intSortOrder" value="<?= esc($menu['intSortOrder']) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="txtDesc" class="form-label">Description</label>
                    <textarea name="txtDesc" class="form-control"><?= esc($menu['txtDesc']) ?></textarea>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="bitActive" id="bitActive" class="form-check-input" <?= $menu['bitActive'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="bitActive">Active</label>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Menu
                    </button>
                    <a href="<?= base_url('menu') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>