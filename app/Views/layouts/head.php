<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Multi-tenant booking system for various business types" />
    <meta name="author" content="Your Company Name" />
    <meta name="keywords" content="booking system, multi-tenant, SaaS, reservation system" />

    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="<?= $title ?? 'Smart Booking System' ?>" />
    <meta property="og:description" content="Multi-tenant booking system for various business types" />
    <meta property="og:image" content="<?= base_url('assets/img/og-image.jpg') ?>" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon.png') ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/img/apple-touch-icon.png') ?>" />    <title><?= $title ?? 'Smart Booking System' ?> | <?= isset($tenant) ? esc($tenant['txtTenantName']) : 'Multi-Tenant Platform' ?></title>

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css') ?>" />

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- DataTables Bootstrap 5 Theme -->
    <link rel="stylesheet" href="<?= base_url('assets/css/datatables/dataTables.bootstrap5.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/datatables/responsive.bootstrap5.min.css') ?>" />

    <!-- Other CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/select2/select2.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/sweetalert2/sweetalert2.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/flatpickr/flatpickr.min.css') ?>" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>" />    <?php if (isset($tenant) && isset($tenant['intTenantID'])): ?>
        <?php
        // Load tenant custom CSS if exists
        $cssPath = FCPATH . 'uploads/tenants/css/' . ($tenant['intTenantID'] ?? '0') . '_custom.css';
        if (file_exists($cssPath)): ?>
            <link rel="stylesheet" href="<?= base_url('uploads/tenants/css/' . ($tenant['intTenantID'] ?? '0') . '_custom.css') ?>" />
        <?php endif; ?>
    <?php endif; ?>

    <!-- Preconnect to External Resources -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- PWA Support -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>" />
    <meta name="theme-color" content="#ffffff" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>" />

    <?= $this->renderSection('head') ?>
</head>