<!-- index.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<a href="<?= base_url('user/create'); ?>" class="btn btn-primary mb-3">Add User</a>
<div id="liveAlertPlaceholder"></div>
<div class="col-12 mb-1">
    <table id="userTable" class="table table-responsive">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Nick</th>
                <th>Employee ID</th>
                <th>Photo</th>
                <th>Join Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan dimuat secara dinamis oleh DataTable -->
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>