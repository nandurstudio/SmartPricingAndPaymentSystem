<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mt-4 mb-0">
                <i class="bi bi-pencil-square me-2"></i><?= $pageTitle ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/') ?>" class="text-decoration-none">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('schedules') ?>" class="text-decoration-none">
                            <i class="bi bi-clock"></i> Schedules
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-pencil-square"></i> <?= $pageTitle ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-pencil-square me-1"></i>
                    <?= $pageTitle ?>
                </h6>
            </div>
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
            <?php endif; ?>
            <form action="<?= base_url('schedules/update/' . $schedule['intScheduleID']) ?>" method="post">
                <?= csrf_field() ?>
                <?= $this->include('schedules/_form', ['schedule' => $schedule]) ?>
                <?php if (!empty($bookings)) : ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Warning:</strong> This schedule has <?= count($bookings) ?> existing bookings. Changing times may affect these bookings.
                    </div>
                <?php endif; ?>
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <?php
                    // Smart Cancel: fallback ke service pertama jika tidak ada service_id di URL
                    $cancelServiceId = $_GET['service_id'] ?? ($services[0]['intServiceID'] ?? null);
                    $cancelUrl = base_url('schedules');
                    if ($cancelServiceId) {
                        $cancelUrl .= '?service_id=' . $cancelServiceId;
                    }
                    ?>
                    <a href="<?= $cancelUrl ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>Update Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
