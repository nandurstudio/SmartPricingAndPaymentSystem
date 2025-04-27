<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang, <?= esc(session()->get('userFullName')) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <?php if (session()->has('userID')): ?>
            <div class="alert alert-success">
                <h1>Selamat Datang, <?= session()->get('userFullName') ?>!</h1>
                <p>Email Anda: <?= session()->get('userEmail') ?></p>
                <p>Peran Anda: <?= session()->get('roleID') ?></p>
                <p>Waktu Bergabung: <?= session()->get('joinDate') ?></p>
                <p>Terakhir Login: <?= session()->get('lastLogin') ?></p>
            </div>
            <a href="<?= site_url('/logout') ?>" class="btn btn-danger">Logout</a>
        <?php else: ?>
            <div class="alert alert-danger">
                <h1>Anda belum login!</h1>
                <p>Silakan login untuk melanjutkan.</p>
                <a href="<?= site_url('auth/login') ?>" class="btn btn-primary">Login</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js"></script>
</body>

</html>