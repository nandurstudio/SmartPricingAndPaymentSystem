<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-building me-1"></i>
                    <?= $pageTitle ?>
                </div>
                <a href="<?= base_url('tenant/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Create New Tenant
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Owner</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tenants)) : ?>
                        <?php foreach ($tenants as $tenant) : ?>
                            <tr>
                                <td><?= $tenant['id'] ?></td>
                                <td><?= esc($tenant['name']) ?></td>
                                <td><?= esc($tenant['type']) ?></td>
                                <td>
                                    <?php if ($tenant['is_active'] == 1) : ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= isset($tenant['owner_name']) ? esc($tenant['owner_name']) : session()->get('userFullName') ?></td>
                                <td><?= date('Y-m-d', strtotime($tenant['created_date'] ?? date('Y-m-d'))) ?></td>
                                <td>
                                    <a href="<?= base_url('tenant/view/' . $tenant['id']) ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('tenant/edit/' . $tenant['id']) ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">No tenants found. Create your first tenant to get started.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
