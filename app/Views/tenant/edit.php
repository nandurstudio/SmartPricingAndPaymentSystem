<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('tenant') ?>">Tenants</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
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
            <?php endif; ?>

            <form action="<?= base_url('tenant/update/' . $tenant['id']) ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?? $tenant['name'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="" disabled>Select Tenant Type</option>
                            <option value="Futsal" <?= (old('type') ?? $tenant['type']) == 'Futsal' ? 'selected' : '' ?>>Futsal</option>
                            <option value="Villa" <?= (old('type') ?? $tenant['type']) == 'Villa' ? 'selected' : '' ?>>Villa</option>
                            <option value="Salon" <?= (old('type') ?? $tenant['type']) == 'Salon' ? 'selected' : '' ?>>Salon</option>
                            <option value="Course" <?= (old('type') ?? $tenant['type']) == 'Course' ? 'selected' : '' ?>>Course/Training</option>
                            <option value="Restaurant" <?= (old('type') ?? $tenant['type']) == 'Restaurant' ? 'selected' : '' ?>>Restaurant</option>
                            <option value="Workspace" <?= (old('type') ?? $tenant['type']) == 'Workspace' ? 'selected' : '' ?>>Co-working Space</option>
                            <option value="Other" <?= (old('type') ?? $tenant['type']) == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= old('description') ?? $tenant['description'] ?></textarea>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contact_email" class="form-label">Contact Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?= old('contact_email') ?? $tenant['contact_email'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?= old('contact_phone') ?? $tenant['contact_phone'] ?>" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1" <?= (old('is_active') ?? $tenant['is_active']) == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= (old('is_active') ?? $tenant['is_active']) == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Tenant</button>
                    <a href="<?= base_url('tenant') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
