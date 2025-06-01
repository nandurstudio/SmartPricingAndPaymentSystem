<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Role Management</h5>
                <a href="<?= base_url('roles/create') ?>" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i> Add New Role
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="roleTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>  <!-- Changed from '#' to 'No.' -->
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
<!-- Debug CSS loading -->
<link href="<?= base_url('assets/css/pages/role/role.css?v=' . time()) ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/pages/role/role.js') ?>"></script>
<?= $this->endSection() ?>