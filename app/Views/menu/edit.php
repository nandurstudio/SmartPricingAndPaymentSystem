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
            <?php endif ?>            <form action="<?= base_url('menu/update/' . $menu['intMenuID']) ?>" method="post">
                <?= csrf_field() ?>
                
                <?= $this->include('menu/_form') ?>

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