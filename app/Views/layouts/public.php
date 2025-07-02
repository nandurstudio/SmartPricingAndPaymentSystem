<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Smart Pricing System - Multi-tenant pricing management" />
    <meta name="author" content="Your Company Name" />
    <title><?= $title ?? 'Smart Pricing System' ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon.png') ?>" />
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css') ?>" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css') ?>" />
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/themes/default.css') ?>" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>" />
    
    <?= $this->renderSection('styles') ?>
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 72px; /* Account for fixed navbar */
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
        }
        .navbar-brand {
            font-weight: 600;
            color: #2c3e50;
        }
        .navbar-brand i {
            color: #3498db;
        }
        .nav-link {
            font-weight: 500;
            color: #2c3e50;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #3498db;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border-radius: 0.5rem;
        }
        .footer {
            background-color: #fff;
            padding: 1.5rem 0;
            margin-top: auto;
            box-shadow: 0 -2px 4px rgba(0,0,0,.1);
        }
        .main-content {
            flex: 1 0 auto;
            padding: 2rem 0;
        }
        @media (max-width: 768px) {
            body {
                padding-top: 62px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url() ?>">
                <i class="bi bi-graph-up me-2"></i>
                Smart Pricing System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login') ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('register') ?>">
                            <i class="bi bi-person-plus me-1"></i> Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?= date('Y') ?> Smart Pricing System. All rights reserved.</p>
                </div>
                <div class="col-12 col-md-6 text-center text-md-end">
                    <a href="<?= base_url('terms') ?>" class="text-decoration-none text-muted me-3">Terms of Service</a>
                    <a href="<?= base_url('privacy-policy') ?>" class="text-decoration-none text-muted">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Core Scripts -->
    <script src="<?= base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
    
    <script>
        // Handle navbar background on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            function updateNavbar() {
                if (window.scrollY > 10) {
                    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                    navbar.style.backdropFilter = 'blur(5px)';
                } else {
                    navbar.style.backgroundColor = '#fff';
                    navbar.style.backdropFilter = 'none';
                }
            }
            window.addEventListener('scroll', updateNavbar);
            updateNavbar();
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>
