<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('roles') ?>">Role Management</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">                <div>
                    <i class="bi bi-shield-plus me-1"></i>
                    <?= $pageTitle ?>
                </div>
                <a href="<?= base_url('roles') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <?= $this->include('layouts/messages') ?>
            <form action="<?= base_url('roles/store') ?>" method="post" id="roleForm">
                <?= $this->include('role/_form') ?>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>