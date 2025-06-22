<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('service-types') ?>">Service Types</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="bi bi-pencil-square me-1"></i>
                                Edit Service Type
                            </h5>
                            <div class="small text-muted">
                                Update service type details
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('service-types') ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                            <?php if ($serviceType['bitActive']): ?>
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm toggle-status" 
                                    data-id="<?= $serviceType['intServiceTypeID'] ?>" 
                                    data-status="1" 
                                    title="Deactivate Service Type">
                                <i class="bi bi-toggle-off me-1"></i> Deactivate
                            </button>
                            <?php else: ?>
                            <button type="button" 
                                    class="btn btn-outline-success btn-sm toggle-status" 
                                    data-id="<?= $serviceType['intServiceTypeID'] ?>" 
                                    data-status="0" 
                                    title="Activate Service Type">
                                <i class="bi bi-toggle-on me-1"></i> Activate
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Error
                            </h6>
                            <hr>
                            <p class="mb-0"><?= session()->getFlashdata('error') ?></p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Please correct the following errors:
                            </h6>
                            <hr>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif ?>

                    <?= $this->include('service-type/_form') ?>
                </div>

                <?php if ($serviceType['txtCreatedBy'] || $serviceType['dtmCreatedDate']): ?>
                <div class="card-footer">
                    <div class="row text-muted small">
                        <div class="col-md-6">
                            <i class="bi bi-person-plus me-1"></i> Created by: <?= esc($serviceType['txtCreatedBy']) ?>
                            <br>
                            <i class="bi bi-calendar-plus me-1"></i> Created on: <?= date('Y-m-d H:i:s', strtotime($serviceType['dtmCreatedDate'])) ?>
                        </div>
                        <?php if ($serviceType['txtUpdatedBy'] || $serviceType['dtmUpdatedDate']): ?>
                        <div class="col-md-6 text-md-end">
                            <i class="bi bi-person-gear me-1"></i> Last updated by: <?= esc($serviceType['txtUpdatedBy']) ?>
                            <br>
                            <i class="bi bi-calendar-check me-1"></i> Last updated on: <?= date('Y-m-d H:i:s', strtotime($serviceType['dtmUpdatedDate'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/pages/service-types.js') ?>"></script>
<?= $this->endSection() ?>
