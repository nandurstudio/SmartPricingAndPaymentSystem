<div class="mb-3">
    <label for="txtUserName" class="form-label">Username</label>
    <input type="text" class="form-control" id="txtUserName" name="txtUserName" value="<?= esc($user['txtUserName'] ?? '') ?>" required>
</div>

<div class="mb-3">
    <label for="txtFullName" class="form-label">Full Name</label>
    <input type="text" class="form-control" id="txtFullName" name="txtFullName" value="<?= esc($user['txtFullName'] ?? '') ?>" required>
</div>

<div class="mb-3">
    <label for="txtNick" class="form-label">Nickname</label>
    <input type="text" class="form-control" id="txtNick" name="txtNick" value="<?= esc($user['txtNick'] ?? '') ?>" maxlength="3" required>
</div>

<div class="mb-3">
    <label for="txtEmpID" class="form-label">Employee ID</label>
    <input type="text" class="form-control" id="txtEmpID" name="txtEmpID" value="<?= esc($user['txtEmpID'] ?? '') ?>" required>
</div>

<div class="mb-3">
    <label for="txtEmail" class="form-label">Email</label>
    <input type="email" class="form-control" id="txtEmail" name="txtEmail" value="<?= esc($user['txtEmail'] ?? '') ?>" required>
</div>

<div class="mb-3">
    <label for="intRoleID" class="form-label">Role</label>
    <select class="form-control" id="intRoleID" name="intRoleID" required>
        <?php foreach ($roles as $role): ?>
            <option value="<?= esc($role['intRoleID']) ?>" <?= $role['intRoleID'] == ($user['intRoleID'] ?? '') ? 'selected' : '' ?>><?= esc($role['txtRoleName']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="intJobTitleID" class="form-label">Job Title</label>
    <select class="form-control" id="intJobTitleID" name="intJobTitleID">
        <?php foreach ($jobTitles as $jobTitle): ?>
            <option value="<?= esc($jobTitle['intJobTitleID']) ?>" <?= $jobTitle['intJobTitleID'] == ($user['intJobTitleID'] ?? '') ? 'selected' : '' ?>><?= esc($jobTitle['txtJobTitle']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="intSupervisorID" class="form-label">Supervisor</label>
    <select class="form-control" id="intSupervisorID" name="intSupervisorID">
        <option value="">-- No Supervisor --</option>
        <?php foreach ($supervisors as $supervisor): ?>
            <option value="<?= esc($supervisor['intUserID']) ?>" <?= $supervisor['intUserID'] == ($user['intSupervisorID'] ?? '') ? 'selected' : '' ?>><?= esc($supervisor['txtFullName']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="intLineID" class="form-label">Line</label>
    <select class="form-control" id="intLineID" name="intLineID">
        <?php foreach ($lines as $line): ?>
            <option value="<?= esc($line['intLineID']) ?>" <?= $line['intLineID'] == ($user['intLineID'] ?? '') ? 'selected' : '' ?>><?= esc($line['txtLine']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="intDepartmentID" class="form-label">Department</label>
    <select class="form-control" id="intDepartmentID" name="intDepartmentID">
        <?php foreach ($departments as $department): ?>
            <option value="<?= esc($department['intDepartmentID']) ?>" <?= $department['intDepartmentID'] == ($user['intDepartmentID'] ?? '') ? 'selected' : '' ?>><?= esc($department['txtDepartmentName']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="txtPassword" class="form-label">Password</label>
    <input type="password" class="form-control" id="txtPassword" name="txtPassword">
    <small class="form-text text-muted">Leave blank to keep current password.</small>
</div>

<div class="mb-3">
    <label for="bitActive" class="form-label">Active</label>
    <input type="checkbox" class="form-check-input" id="bitActive" name="bitActive" <?= ($user['bitActive'] ?? 0) ? 'checked' : '' ?>>
    <label class="form-check-label" for="bitActive">User is Active</label>
</div>

<div class="mb-3">
    <label for="txtPhoto" class="form-label">Photo</label>
    <?php if (!empty($user['txtPhoto'])): ?>
        <div class="mb-3">
            <img id="previewPhoto" src="<?= base_url('uploads/photos/' . $user['txtPhoto']) ?>" alt="User Photo" class="img-thumbnail" width="150">
        </div>
    <?php else: ?>
        <div class="mb-3">
            <img id="previewPhoto" src="<?= base_url('uploads/photos/default.jpg') ?>" alt="Default Photo" class="img-thumbnail" width="150">
        </div>
    <?php endif; ?>
    <input type="file" class="form-control" id="txtPhoto" name="txtPhoto" accept="image/*" onchange="previewImage(event)">
    <small class="form-text text-muted">Leave blank to keep current photo.</small>
</div>

<div class="mb-3">
    <label for="dtmJoinDate" class="form-label">Join Date</label>
    <input type="datetime-local" class="form-control" id="dtmJoinDate" name="dtmJoinDate" value="<?= esc($user['dtmJoinDate'] ?? '') ?>" required>
</div>