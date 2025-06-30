<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Version: 2.0 - Cache Buster -->
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="bi bi-pencil-square text-primary me-2"></i>
        Edit Service Attribute
    </h1>
    <ol class="breadcrumb mb-4" aria-label="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('services') ?>">Services</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Service Attribute</li>
    </ol>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
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
                                Edit Service Attribute
                            </h5>
                            <div class="small text-muted">
                                Update service attribute details
                            </div>
                        </div>
                        <a href="<?= base_url('service-attributes') ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Please correct the following errors:
                            </h6>
                            <hr>
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?= $this->include('service-attributes/_form', ['form_action' => base_url('service-attributes/update/' . $attribute->intAttributeID)]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fieldTypeSelect = document.getElementById('txtFieldType');
    const optionsContainer = document.getElementById('optionsContainer');
    function toggleOptionsContainer() {
        if (fieldTypeSelect.value === 'select') {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    }
    fieldTypeSelect.addEventListener('change', toggleOptionsContainer);
    toggleOptionsContainer(); // Run on page load
});
</script>
<?= $this->endSection() ?>
