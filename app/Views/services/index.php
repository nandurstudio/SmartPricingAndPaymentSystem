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
                    <i class="fas fa-concierge-bell me-1"></i>
                    <?= $pageTitle ?>
                </div>
                <a href="<?= base_url('services/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Create New Service
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($tenants) && count($tenants) > 1) : ?>
                <div class="mb-3">
                    <label for="tenant-filter" class="form-label">Filter by Tenant:</label>
                    <select class="form-select" id="tenant-filter">
                        <option value="">All Tenants</option>
                        <?php foreach ($tenants as $tenant) : ?>
                            <option value="<?= $tenant['id'] ?>" <?= (isset($selected_tenant) && $selected_tenant == $tenant['id']) ? 'selected' : '' ?>>
                                <?= esc($tenant['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <script>
                    document.getElementById('tenant-filter').addEventListener('change', function() {
                        const tenantId = this.value;
                        if (tenantId) {
                            window.location.href = '<?= base_url('services') ?>?tenant_id=' + tenantId;
                        } else {
                            window.location.href = '<?= base_url('services') ?>';
                        }
                    });
                </script>
            <?php endif; ?>
            
            <table id="datatablesSimple" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Tenant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($services)) : ?>
                        <?php foreach ($services as $service) : ?>                            <tr>
                                <td><?= $service['intServiceID'] ?></td>
                                <td><?= esc($service['txtName']) ?></td>
                                <td><?= esc($service['type_name'] ?? 'N/A') ?></td>
                                <td><?= number_format($service['decPrice'], 2) ?></td>
                                <td><?= $service['intDuration'] ?> minutes</td>
                                <td>
                                    <?php if ($service['bitActive'] == 1) : ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($service['tenant_name'] ?? 'N/A') ?></td>
                                <td>
                                    <a href="<?= base_url('services/view/' . $service['intServiceID']) ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('services/edit/' . $service['intServiceID']) ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('schedule?service_id=' . $service['intServiceID']) ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-calendar"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center">No services found. Create your first service to get started.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
