<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">                <div>
                    <i class="bi bi-shield me-1"></i>
                    <?= $pageTitle ?>
                </div>
                <a href="<?= base_url('roles/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add New Role
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="roleTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Notes</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th>Updated By</th>
                        <th>Updated Date</th>
                        <th style="width: 100px">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link href="<?= base_url('assets/css/pages/role/role.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/pages/role/role.js') ?>"></script>
<?= $this->endSection() ?>