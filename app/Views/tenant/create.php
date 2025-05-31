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
            <i class="fas fa-plus-circle me-1"></i>
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

            <form action="<?= base_url('tenant/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="" selected disabled>Select Tenant Type</option>
                            <option value="Futsal" <?= old('type') == 'Futsal' ? 'selected' : '' ?>>Futsal</option>
                            <option value="Villa" <?= old('type') == 'Villa' ? 'selected' : '' ?>>Villa</option>
                            <option value="Salon" <?= old('type') == 'Salon' ? 'selected' : '' ?>>Salon</option>
                            <option value="Course" <?= old('type') == 'Course' ? 'selected' : '' ?>>Course/Training</option>
                            <option value="Restaurant" <?= old('type') == 'Restaurant' ? 'selected' : '' ?>>Restaurant</option>
                            <option value="Workspace" <?= old('type') == 'Workspace' ? 'selected' : '' ?>>Co-working Space</option>
                            <option value="Other" <?= old('type') == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= old('description') ?></textarea>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contact_email" class="form-label">Contact Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?= old('contact_email') ?? session()->get('userEmail') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?= old('contact_phone') ?>" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="agreeTos" required>
                        <label class="form-check-label" for="agreeTos">
                            I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Create Tenant</button>
                    <a href="<?= base_url('tenant') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
