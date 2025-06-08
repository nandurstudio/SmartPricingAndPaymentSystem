<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Service Type Attributes</h4>
                <a href="<?= base_url('service-attributes/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add New Attribute
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->has('message')): ?>
                    <div class="alert alert-<?= session()->get('message_type') ?>">
                        <?= session()->get('message') ?>
                    </div>
                <?php endif; ?>

                <?php foreach ($serviceTypes as $serviceType): ?>
                    <div class="card mb-4">
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
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Label</th>
                                                <th>Field Type</th>
                                                <th>Required</th>
                                                <th>Default Value</th>
                                                <th>Display Order</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($serviceType['attributes'] as $attr): ?>
                                                <tr>
                                                    <td><?= esc($attr['txtName']) ?></td>
                                                    <td><?= esc($attr['txtLabel']) ?></td>
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
                                                    <td>
                                                        <a href="<?= base_url('service-attributes/edit/' . $attr['intAttributeID']) ?>" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
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
</div>
<?= $this->endSection() ?>
