<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('services') ?>">Services</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-plus-circle me-1"></i>
            <?= $pageTitle ?>
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>            <?php
            $tenantId = $_GET['tenant_id'] ?? null;
            $formAction = base_url('services/store');
            if ($tenantId) {
                $formAction .= '?tenant_id=' . $tenantId;
            }
            ?>
            <form action="<?= $formAction ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="intTenantID" value="<?= $tenantId ?>">
                <?= $this->include('services/_form') ?>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/pages/service-form.js') ?>"></script>
<?= $this->endSection() ?>
