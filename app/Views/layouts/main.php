<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <?= $this->include('layouts/head') ?>
    <title><?= $title ?? 'Smart Pricing System' ?></title>
    <?= $this->renderSection('css') ?>
    <?= $this->renderSection('themes') ?>
</head>
<body class="nav-fixed theme-light">
    <?= $this->include('layouts/nav') ?>
    
    <div id="layoutSidenav">
        <?= $this->include('layouts/sidenav') ?>
        
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
            
            <?= $this->include('layouts/footer') ?>
        </div>
    </div>
    
    <?= $this->include('layouts/scripts') ?>
</body>
</html>