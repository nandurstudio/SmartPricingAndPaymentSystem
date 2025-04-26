<!-- view.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h2>Detail User</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Username:</strong> <?= esc($user['txtUserName']) ?></p>
            <p><strong>Full Name:</strong> <?= esc($user['txtFullName']) ?></p>
            <p><strong>Email:</strong> <?= esc($user['txtEmail']) ?></p>
            <p><strong>Role:</strong> <?= esc($role['txtRoleName']) ?> <!-- Pastikan field role_name ada di tabel roles --></p>
            <p><strong>Supervisor:</strong> <?= esc($supervisorName) ?></p>
            <p><strong>Join Date:</strong> <?= esc($user['dtmJoinDate']) ?></p>

            <!-- Tambahkan foto user -->
            <p><strong>Photo:</strong>
                <?php if ($user['txtPhoto'] !== 'default.jpg'): ?>
                    <img src="<?= base_url('uploads/photos/' . $user['txtPhoto']) ?>" alt="User Photo" style="width: 100px; height: 100px; object-fit: cover;">
                <?php else: ?>
                    <span>No photo</span>
                <?php endif; ?>
            </p>

            <!-- Anda bisa menambahkan lebih banyak data sesuai kebutuhan -->
        </div>
    </div>
    <a href="<?= base_url('/user') ?>" class="btn btn-secondary mt-3">Back to List</a>
</div>
<?= $this->endSection() ?>