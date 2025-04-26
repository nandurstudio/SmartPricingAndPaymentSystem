<?= $this->extend('layouts/starter/main') ?>

<?= $this->section('content') ?>
<h1>Edit User Job Title</h1>

<form action="<?= base_url('transactions/user_jobtitle/update/' . $userJobTitle['intTrUserJobTitleID']) ?>" method="POST">
    <?= csrf_field() ?>

    <!-- Hidden Input for intUserID -->
    <input type="hidden" name="intUserID" value="<?= $userJobTitle['intUserID'] ?>">

    <div class="mb-3">
        <label for="userName" class="form-label">User</label>
        <input type="text" class="form-control" id="userName" value="<?= $userJobTitle['userName'] ?>" disabled>
    </div>

    <div class="mb-3">
        <label for="jobTitle" class="form-label">Job Title</label>
        <select name="intJobTitleID" id="jobTitle" class="form-control">
            <?php foreach ($jobTitles as $jobTitle): ?>
                <option value="<?= $jobTitle['intJobTitleID'] ?>" <?= $userJobTitle['intJobTitleID'] == $jobTitle['intJobTitleID'] ? 'selected' : '' ?>>
                    <?= $jobTitle['txtJobTitle'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="achieved" name="bitAchieved" value="1" <?= $userJobTitle['bitAchieved'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="achieved">Achieved</label>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('transactions/user_jobtitle') ?>" class="btn btn-secondary">Cancel</a>
</form>

<?= $this->endSection() ?>