<!-- menu/create.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Create New Menu</h2>
    <form action="<?= base_url('/menu/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="txtMenuName" class="form-label">Menu Name</label>
            <input type="text" name="txtMenuName" class="form-control" id="txtMenuName" required>
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