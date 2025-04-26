<!-- app/Views/role_menu_access/create.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <h2>Create Role Menu Access</h2>

    <form action="<?= base_url('role_menu_access/store') ?>" method="post">
        <div class="form-group">
            <label for="role_id">Role</label>
            <select name="intRoleID" id="role_id" class="form-control" required>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['intRoleID'] ?>"><?= $role['txtRoleName'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="menu_id">Menu</label>
            <select name="intMenuID" id="menu_id" class="form-control" required>
                <?php foreach ($menus as $menu): ?>
                    <option value="<?= $menu['intMenuID'] ?>"><?= $menu['txtMenuName'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="can_view">Can View</label>
            <input type="checkbox" name="bitCanView" id="can_view" value="1">
        </div>

        <div class="form-group">
            <label for="can_add">Can Add</label>
            <input type="checkbox" name="bitCanAdd" id="can_add" value="1">
        </div>

        <div class="form-group">
            <label for="can_edit">Can Edit</label>
            <input type="checkbox" name="bitCanEdit" id="can_edit" value="1">
        </div>

        <div class="form-group">
            <label for="can_delete">Can Delete</label>
            <input type="checkbox" name="bitCanDelete" id="can_delete" value="1">
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
        <a href="<?= base_url('role_menu_access') ?>" class="btn btn-secondary">Cancel</a>
    </form>

</div>
<?= $this->endSection(); ?>