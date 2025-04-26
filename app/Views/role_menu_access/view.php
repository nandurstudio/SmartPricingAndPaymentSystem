<!-- app/Views/role_menu_access/view.php -->
<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<div class="container">
    <!-- app/Views/role_menu_access/view.php -->
    <h4>Role Menu Access Details</h4>
    <p><strong>ID:</strong> <?= $roleMenuAccess['intRoleMenuAccessID'] ?></p>
    <p><strong>Role:</strong>
        <?php
        // Temukan nama role berdasarkan ID
        $role = array_filter($roles, fn($r) => $r['intRoleID'] == $roleMenuAccess['intRoleID']);
        echo !empty($role) ? reset($role)['txtRoleName'] : 'Unknown';
        ?>
    </p>
    <p><strong>Menu:</strong>
        <?php
        // Temukan nama menu berdasarkan ID
        $menu = array_filter($menus, fn($m) => $m['intMenuID'] == $roleMenuAccess['intMenuID']);
        echo !empty($menu) ? reset($menu)['txtMenuName'] : 'Unknown';
        ?>
    </p>
    <p><strong>Can View:</strong> <?= $roleMenuAccess['bitCanView'] ? 'Yes' : 'No' ?></p>
    <p><strong>Can Add:</strong> <?= $roleMenuAccess['bitCanAdd'] ? 'Yes' : 'No' ?></p>
    <p><strong>Can Edit:</strong> <?= $roleMenuAccess['bitCanEdit'] ? 'Yes' : 'No' ?></p>
    <p><strong>Can Delete:</strong> <?= $roleMenuAccess['bitCanDelete'] ? 'Yes' : 'No' ?></p>
    <a href="<?= base_url('role_menu_access') ?>" class="btn btn-secondary">Back</a>

</div>
<?= $this->endSection(); ?>