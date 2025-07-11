<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="logo-wrapper mb-3">
                                <?php if (!empty($user['txtPhoto']) && $user['txtPhoto'] !== 'default.png'): ?>
                                    <img src="<?= base_url('uploads/photos/' . $user['txtPhoto']) ?>"
                                         alt="<?= esc($user['txtFullName']) ?>"
                                         class="img-thumbnail rounded-circle"
                                         style="width: 150px; height: 150px; object-fit: cover;"
                                         onerror="this.onerror=null; this.src='<?= base_url('assets/img/default-avatar.png') ?>'; this.classList.add('bg-light');">
                                <?php else: ?>
                                    <?php $rand = rand(1,5); ?>
                                    <img src="<?= base_url('assets/img/illustrations/profiles/profile-' . $rand . '.png') ?>"
                                         alt="Default profile picture"
                                         class="img-thumbnail rounded-circle bg-light"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <table class="table">
                                <tr>
                                    <th width="200">Username</th>
                                    <td><?= esc($user['txtUserName']) ?></td>
                                </tr>
                                <tr>
                                    <th>Full Name</th>
                                    <td><?= esc($user['txtFullName']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= esc($user['txtEmail']) ?></td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td><?= esc($user['txtRoleName']) ?></td>
                                </tr>
                                <tr>
                                    <th>Join Date</th>
                                    <td><?= date('d M Y', strtotime($user['dtmJoinDate'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $user['bitActive'] ? 'success' : 'danger' ?>">
                                            <?= $user['bitActive'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex">
                        <div class="me-2">
                            <a href="<?= base_url('users') ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= base_url('users/view/' . $user['intUserID']) ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= base_url('users/edit/' . $user['intUserID']) ?>" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= base_url('users/delete/' . $user['intUserID']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
