<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('menu') ?>">Menu Management</a></li>
        <li class="breadcrumb-item active">Add New Menu</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Add New Menu
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Please check the form below for errors.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>

            <form action="<?= base_url('menu/create') ?>" method="post">
                <?= csrf_field() ?>

                <?= $this->include('menu/_form') ?>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Menu
                    </button>
                    <a href="<?= base_url('menu') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
        </div>

        <div class="mb-3">
            <label for="txtMenuLink" class="form-label">Menu Link</label>
            <input type="text" name="txtMenuLink" class="form-control" id="txtMenuLink">
        </div>

        <div class="mb-3">
            <label for="txtIcon" class="form-label">Icon</label>
            <input type="text" name="txtIcon" class="form-control" id="txtIcon">
        </div>

        <div class="mb-3">
            <label for="intParentID" class="form-label">Parent ID</label>
            <input type="number" name="intParentID" class="form-control" id="intParentID">
        </div>

        <div class="mb-3">
            <label for="intSortOrder" class="form-label">Sort Order</label>
            <input type="number" name="intSortOrder" class="form-control" id="intSortOrder" value="0">
        </div>

        <div class="mb-3">
            <label for="txtDesc" class="form-label">Description</label>
            <textarea name="txtDesc" class="form-control" id="txtDesc"></textarea>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="bitActive" id="bitActive" class="form-check-input" checked>
            <label class="form-check-label" for="bitActive">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">Create Menu</button>
        <a href="<?= base_url('/menu') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection(); ?>