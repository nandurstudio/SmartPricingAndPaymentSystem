<!-- index.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<a href="<?= base_url('user/create'); ?>" class="btn btn-primary mb-3">Add User</a>
<div id="liveAlertPlaceholder"></div>
<div class="col-12 mb-1">

    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="user"></i></div>
                                Users List
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">
                            <a class="btn btn-sm btn-light text-primary" href="<?= site_url('user/create') ?>">
                                <i class="me-1" data-feather="user-plus"></i>
                                Add New User
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-fluid px-4">
            <div class="card">
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($users as $index => $user): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <img class="avatar-img img-fluid" src="<?= esc($user['txtPhoto'] ?: 'assets/img/illustrations/profiles/default.png') ?>" />
                                            </div>
                                            <?= esc($user['txtFullName']) ?>
                                        </div>
                                    </td>
                                    <td><?= esc($user['txtEmail']) ?></td>
                                    <td><?= esc($user['txtRoleName']) ?></td>
                                    <td><?= date('d M Y', strtotime($user['dtmJoinDate'])) ?></td>
                                    <td>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="<?= site_url('user/edit/' . $user['intUserID']) ?>"><i data-feather="edit"></i></a>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark" href="<?= site_url('user/delete/' . $user['intUserID']) ?>" onclick="return confirm('Are you sure you want to delete this user?')"><i data-feather="trash-2"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable({
                responsive: true,
                autoWidth: false,
                searching: true,
                ordering: true
            });
        });
    </script>
</div>
<?= $this->endSection() ?>