<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($tenant['txtTenantName']) ?></title>
    
    <?php 
    $settings = json_decode($tenant['jsonSettings'] ?? '{}', true);
    $theme = $settings['theme'] ?? 'light';
    $primaryColor = $settings['primaryColor'] ?? '#0d6efd';
    $headerStyle = $settings['headerStyle'] ?? 'default';
    
    // Get the current protocol and domain
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $domain = $_SERVER['HTTP_HOST'];
    ?>

    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= $protocol ?>://<?= $domain ?>/manifest.json" crossorigin="use-credentials">
    
    <!-- Favicon -->
    <?php if (!empty($tenant['txtLogo'])): ?>
        <link rel="icon" type="image/png" href="<?= get_tenant_logo_url($tenant['txtLogo']) ?>">
    <?php endif; ?>    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/themes/' . $theme . '.css') ?>">
    
    <!-- Custom Tenant CSS -->
    <?php if (file_exists(FCPATH . 'uploads/tenants/css/' . $tenant['intTenantID'] . '_custom.css')): ?>
        <link rel="stylesheet" href="<?= get_tenant_css_url($tenant['intTenantID']) ?>">
    <?php endif; ?>

    <style>
        :root {
            --tenant-primary: <?= $primaryColor ?>;
        }
    </style>
</head>
<body class="theme-<?= $theme ?>">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-<?= $theme ?> bg-<?= $headerStyle === 'transparent' ? 'transparent' : ($theme === 'dark' ? 'dark' : 'light') ?> <?= $headerStyle === 'fixed' ? 'fixed-top' : '' ?>">
        <div class="container">            <a class="navbar-brand d-flex align-items-center" href="<?= tenant_url('') ?>">
                <?php if (!empty($tenant['txtLogo'])): ?>
                    <img src="<?= get_tenant_logo_url($tenant['txtLogo']) ?>" 
                         alt="<?= esc($tenant['txtTenantName']) ?>"
                         class="me-2"
                         style="height: 40px;">
                <?php else: ?>
                    <i class="fas fa-building me-2"></i>
                <?php endif; ?>
                <?= esc($tenant['txtTenantName']) ?>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= tenant_url('') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= tenant_url('services') ?>">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= tenant_url('bookings') ?>">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= tenant_url('schedules') ?>">Schedules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= tenant_url('settings') ?>">Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= esc($tenant['txtTenantName']) ?></h5>
                    <p><?= esc($settings['description'] ?? '') ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <?php if (!empty($settings['phone'])): ?>
                        <p><i class="fas fa-phone me-2"></i><?= esc($settings['phone']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($settings['email'])): ?>
                        <p><i class="fas fa-envelope me-2"></i><?= esc($settings['email']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($settings['address'])): ?>
                        <p><i class="fas fa-map-marker-alt me-2"></i><?= esc($settings['address']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> <?= esc($tenant['txtTenantName']) ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?= base_url('assets/js/tenant-website.js') ?>"></script>
</body>
</html>
