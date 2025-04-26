<!-- menu/edit.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Edit Menu</h2>

    <form action="<?= base_url('/menu/update/' . $menu['intMenuID']) ?>" method="post">
        <div class="mb-3">
            <label for="txtMenuName" class="form-label">Menu Name</label>
            <input type="text" name="txtMenuName" value="<?= esc($menu['txtMenuName']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="txtMenuLink" class="form-label">Menu Link</label>
            <input type="text" name="txtMenuLink" value="<?= esc($menu['txtMenuLink']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="txtIcon" class="form-label">Icon (CSS Class)</label>
            <input type="text" name="txtIcon" value="<?= esc($menu['txtIcon']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="intParentID" class="form-label">Parent ID</label>
            <input type="number" name="intParentID" value="<?= esc($menu['intParentID']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="intSortOrder" class="form-label">Sort Order</label>
            <input type="number" name="intSortOrder" value="<?= esc($menu['intSortOrder']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="txtDesc" class="form-label">Description</label>
            <textarea name="txtDesc" class="form-control"><?= esc($menu['txtDesc']) ?></textarea>
        </div>
        <!-- Active Status Field -->
        <div class="form-check mb-3">
            <input type="checkbox" name="bitActive" id="bitActive" class="form-check-input" <?= $menu['bitActive'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="bitActive">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">Update Menu</button>
        <a href="<?= base_url('/menu') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection(); ?>