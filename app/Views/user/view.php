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
                    <div class="row">                        <div class="col-md-3">
                            <?php
                            $photoPath = FCPATH . 'uploads/photos/' . ($user['txtPhoto'] ?? 'default.png');
                            if (!empty($user['txtPhoto']) && file_exists($photoPath)) {
                                $imgSrc = base_url('uploads/photos/' . $user['txtPhoto']);
                            } else {
                                $imgSrc = base_url('assets/img/default-avatar.png');
                            }
                            ?>
                            <img src="<?= $imgSrc ?>" 
                                 class="img-fluid rounded" 
                                 alt="Profile Picture">
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
                    <a href="<?= base_url('user') ?>" class="btn btn-secondary">Back to List</a>
                    <a href="<?= base_url('user/edit/' . $user['intUserID']) ?>" class="btn btn-primary">Edit User</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
