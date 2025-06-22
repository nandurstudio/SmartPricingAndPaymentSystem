<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mt-4 mb-0">
                <i class="bi bi-building-add me-2"></i><?= $pageTitle ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/') ?>" class="text-decoration-none">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('tenants') ?>" class="text-decoration-none">
                            <i class="bi bi-buildings"></i> Tenants
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-plus-circle"></i> <?= $pageTitle ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-building-add me-1"></i>
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
            
            <form action="<?= base_url('tenants/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <!-- Include Shared Form -->
                <?= $this->include('tenants/_form') ?>                <!-- Submit Button -->                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="<?= base_url('tenants') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-building-add me-1"></i> Create Tenant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
