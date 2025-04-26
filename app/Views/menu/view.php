<!-- menu/detail.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Menu Detail</h2>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td><?= esc($menu['intMenuID']) ?></td>
        </tr>
        <tr>
            <th>Menu Name</th>
            <td><?= esc($menu['txtMenuName']) ?></td>
        </tr>
        <tr>
            <th>Link</th>
            <td><?= esc($menu['txtMenuLink']) ?></td>
        </tr>
        <tr>
            <th>Icon</th>
            <td><i class="<?= esc($menu['txtIcon']) ?>"></i></td>
        </tr>
        <tr>
            <th>Parent ID</th>
            <td><?= esc($menu['intParentID']) ?></td>
        </tr>
        <tr>
            <th>Sort Order</th>
            <td><?= esc($menu['intSortOrder']) ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?= esc($menu['txtDesc']) ?></td>
        </tr>
        <tr>
            <th>Inserted By</th>
            <td><?= esc($menu['txtInsertedBy']) ?></td>
        </tr>
        <tr>
            <th>Inserted Date</th>
            <td><?= esc($menu['dtmInsertedDate']) ?></td>
        </tr>
        <tr>
            <th>Updated By</th>
            <td><?= esc($menu['txtUpdatedBy']) ?></td>
        </tr>
        <tr>
            <th>Updated Date</th>
            <td><?= esc($menu['dtmUpdatedDate']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <input type="checkbox" disabled <?= $menu['bitActive'] ? 'checked' : '' ?>> Active
            </td>
        </tr>
    </table>

    <a href="<?= base_url('/menu') ?>" class="btn btn-secondary">Back to Menu List</a>
</div>

<?= $this->endSection(); ?>