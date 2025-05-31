<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Edit User Profile</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <form action="<?= base_url('user/update/' . $user['intUserID']) ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <!-- Username -->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtUserName">Username</label>
                            <input class="form-control" id="txtUserName" name="txtUserName" type="text" 
                                   value="<?= old('txtUserName', $user['txtUserName']) ?>" required>
                        </div>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtFullName">Full Name</label>
                            <input class="form-control" id="txtFullName" name="txtFullName" type="text" 
                                   value="<?= old('txtFullName', $user['txtFullName']) ?>" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtEmail">Email</label>
                            <input class="form-control" id="txtEmail" name="txtEmail" type="email" 
                                   value="<?= old('txtEmail', $user['txtEmail']) ?>" required>
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label class="small mb-1" for="intRoleID">Role</label>
                            <select class="form-select" id="intRoleID" name="intRoleID" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['intRoleID'] ?>" 
                                            <?= $role['intRoleID'] == $user['intRoleID'] ? 'selected' : '' ?>>
                                        <?= esc($role['txtRoleName']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Change Password (Optional) -->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtPassword">New Password <span class="text-muted">(optional)</span></label>
                            <input class="form-control" id="txtPassword" name="txtPassword" type="password" 
                                   placeholder="Leave blank to keep current password">
                            <div class="form-text">Only fill this if you want to change the password</div>
                        </div>

                        <!-- Profile Picture -->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtPhoto">Profile Picture</label>
                            <?php if (!empty($user['txtPhoto'])): ?>
                                <div class="mb-2">
                                    <img src="<?= filter_var($user['txtPhoto'], FILTER_VALIDATE_URL) ? 
                                        $user['txtPhoto'] : base_url('uploads/photos/' . $user['txtPhoto']) ?>" 
                                         alt="Current profile picture" class="rounded" style="max-width: 150px">
                                </div>
                            <?php endif; ?>
                            <input class="form-control" id="txtPhoto" name="txtPhoto" type="file" accept="image/*">
                            <div class="form-text">Allowed formats: JPG, PNG. Max size: 2MB</div>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="bitActive" name="bitActive" value="1"
                                       <?= $user['bitActive'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bitActive">Active Account</label>
                            </div>
                        </div>

                        <button class="btn btn-primary" type="submit">Save changes</button>
                        <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
