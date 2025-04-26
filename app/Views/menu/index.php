<!-- menu/index.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<a href="<?= base_url('/menu/create') ?>" class="btn btn-primary mb-3">Add New Menu</a>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Menu Name</th>
            <th>Link</th>
            <th>Icon</th>
            <th>Parent ID</th>
            <th>Sort Order</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($menus)): ?>
            <?php foreach ($menus as $menu): ?>
                <tr>
                    <td><?= esc($menu['intMenuID']) ?></td>
                    <td><?= esc($menu['txtMenuName']) ?></td>
                    <td><?= esc($menu['txtMenuLink']) ?></td>
                    <td><i class="<?= esc($menu['txtIcon']) ?>"></i></td>
                    <td><?= esc($menu['intParentID']) ?></td>
                    <td><?= esc($menu['intSortOrder']) ?></td>
                    <td>
                        <?= $menu['bitActive'] ? 'Active' : 'Non-Active' ?>
                    </td>
                    <td>
                        <a href="<?= base_url('/menu/view/' . $menu['intMenuID']) ?>" class="btn btn-info btn-sm">View</a>
                        <a href="<?= base_url('/menu/edit/' . $menu['intMenuID']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No menu items found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>