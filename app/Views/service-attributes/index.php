<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="bi bi-list-ul text-primary me-2"></i>
        Service Type Attributes
    </h1>
    <ol class="breadcrumb mb-4" aria-label="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('services') ?>">Services</a></li>
        <li class="breadcrumb-item active" aria-current="page">Service Type Attributes</li>
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

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="bi bi-table me-1 text-primary"></i>
                        Attribute List
                    </h5>
                    <div class="small text-muted">Manage and organize your service type attributes</div>
                </div>
                <a href="<?= base_url('service-attributes/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add New Attribute
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php foreach ($serviceTypes as $serviceType): ?>
                <div class="mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <?= esc($serviceType['txtName']) ?>
                            <small class="text-muted">(<?= esc($serviceType['txtCategory']) ?>)</small>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($serviceType['attributes'])): ?>
                            <p class="text-muted">No attributes defined for this service type.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 100px">Actions</th>
                                            <th>Name</th>
                                            <th>Label</th>
                                            <th>Field Type</th>
                                            <th>Required</th>
                                            <th>Default Value</th>
                                            <th>Display Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($serviceType['attributes'] as $attr): ?>
                                            <tr>
                                                <td class="text-start">
                                                    <div class="btn-group btn-group-sm" role="group" aria-label="Attribute Actions">
                                                        <a href="<?= base_url('service-attributes/edit/' . $attr['intAttributeID']) ?>" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Edit Attribute">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td><?= esc($attr['txtName']) ?></td>
                                                <td><i class="bi bi-tag me-1"></i><?= esc($attr['txtLabel']) ?></td>
                                                <td><?= esc($attr['txtFieldType']) ?></td>
                                                <td>
                                                    <?php if ($attr['bitRequired']): ?>
                                                        <span class="badge bg-primary">Required</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Optional</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= esc($attr['txtDefaultValue']) ?></td>
                                                <td><?= esc($attr['intDisplayOrder']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>