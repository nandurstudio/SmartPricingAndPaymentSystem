<?= $this->include('layouts/head') ?>

<body class="nav-fixed">
    <?= $this->include('layouts/nav') ?>
    <div id="layoutSidenav">
        <?= $this->include('layouts/sidenav') ?>
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                    <div class="container-xl px-4">
                        <div class="page-header-content pt-4">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-4">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon">
                                            <i data-feather="<?= $icon; ?>"></i> <!-- Menampilkan ikon dinamis -->
                                        </div>
                                        <?= $pageTitle; ?> <!-- Menampilkan judul yang dinamis -->
                                    </h1>
                                    <div class="page-header-subtitle">
                                        <?= $pageSubTitle; ?> <!-- Menampilkan sub-judul yang dinamis -->
                                    </div>
                                </div>
                                <div class="col-12 col-xl-auto mt-4">Optional page header content</div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-xl px-4 mt-n10">
                    <div class="card">
                        <div class="card-header">
                            <?= $cardTitle; ?> <!-- Menampilkan judul yang dinamis -->
                        </div>
                        <div class="card-body">
                            <?= $this->renderSection('content') ?>
                        </div>
                    </div>
                </div>
            </main>
            <?= $this->include('layouts/footer') ?>
        </div>
    </div>
    <?= $this->include('layouts/scripts') ?>
</body>

</html>