<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Smart Pricing and Payment System" />
    <meta name="author" content="Kelompok 5" />
    <title><?= $title ?? 'Authentication - Smart Pricing System' ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css'); ?>" />
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-light">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <?= $this->renderSection('content') ?>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="footer-admin py-4 bg-light mt-auto">
                <div class="container-xl px-4">
                    <div class="row align-items-center justify-content-between small">
                        <div class="col-auto">
                            <div class="small m-0 text-muted">Copyright &copy; Smart Pricing System <?= date('Y') ?></div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?= $this->renderSection('scripts') ?>
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
