<!-- app/Views/role_menu_access/index.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<a href="<?= base_url('role_menu_access/create') ?>" class="btn btn-primary">Add Role Menu Access</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Role</th>
            <th>Menu</th>
            <th>Can View</th>
            <th>Can Add</th>
            <th>Can Edit</th>
            <th>Can Delete</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($roleMenuAccess as $access): ?>
            <tr>
                <td><?= $access['intRoleMenuAccessID'] ?></td>
                <td>
                    <?php
                    // Temukan nama role berdasarkan ID
                    $role = array_filter($roles, fn($r) => $r['intRoleID'] == $access['intRoleID']);
                    echo !empty($role) ? reset($role)['txtRoleName'] : 'Unknown';
                    ?>
                </td>
                <td>
                    <?php
                    // Temukan nama menu berdasarkan ID
                    $menu = array_filter($menus, fn($m) => $m['intMenuID'] == $access['intMenuID']);
                    echo !empty($menu) ? reset($menu)['txtMenuName'] : 'Unknown';
                    ?>
                </td>
                <td><?= $access['bitCanView'] ? 'Yes' : 'No' ?></td>
                <td><?= $access['bitCanAdd'] ? 'Yes' : 'No' ?></td>
                <td><?= $access['bitCanEdit'] ? 'Yes' : 'No' ?></td>
                <td><?= $access['bitCanDelete'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="<?= base_url("role_menu_access/view/{$access['intRoleMenuAccessID']}") ?>" class="btn btn-info">View</a>
                    <a href="<?= base_url("role_menu_access/edit/{$access['intRoleMenuAccessID']}") ?>" class="btn btn-warning">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>