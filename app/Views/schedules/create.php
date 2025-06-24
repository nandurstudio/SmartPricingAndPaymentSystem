<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('schedules') ?>">Schedules</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-plus-circle me-1"></i>
                    <?= $pageTitle ?>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h4>Error:</h4>
                            <ul>
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?> <form action="<?= base_url('schedules/store') ?>" method="post">
                        <?= $this->include('schedules/_form') ?>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <?php
                            // Smart Cancel: fallback ke service pertama jika tidak ada service_id di URL
                            $cancelServiceId = $_GET['service_id'] ?? ($services[0]['intServiceID'] ?? null);
                            $cancelUrl = base_url('schedules');
                            if ($cancelServiceId) {
                                $cancelUrl .= '?service_id=' . $cancelServiceId;
                            }
                            ?>
                            <a href="<?= $cancelUrl ?>" class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/pages/schedules/schedules.js') ?>"></script>
<?= $this->endSection() ?>