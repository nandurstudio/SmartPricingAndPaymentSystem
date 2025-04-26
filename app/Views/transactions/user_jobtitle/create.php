<?= $this->extend('layouts/starter/main') ?>

<?= $this->section('content') ?>
<h1>Add User Job Title</h1>
<form method="post" action="<?= base_url('transactions/user_jobtitle/store') ?>">
    <div class="mb-3">
        <label for="intUserID">User</label>
        <select name="intUserID" class="form-control" required>
            <option value="">Select User</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['intUserID'] ?>"><?= $user['txtFullName'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="intJobTitleID">Job Title</label>
        <select name="intJobTitleID" class="form-control" required>
            <option value="">Select Job Title</option>
            <?php foreach ($jobTitles as $jobTitle): ?>
                <option value="<?= $jobTitle['intJobTitleID'] ?>"><?= $jobTitle['txtJobTitle'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="intLineID">Line</label>
        <select name="intLineID" class="form-control" required>
            <option value="">Select Line</option>
            <?php foreach ($lines as $line): ?>
                <option value="<?= $line['intLineID'] ?>"><?= $line['txtLine'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="bitAchieved">Achieved</label>
        <select name="bitAchieved" class="form-control">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Save</button>
</form>

<?= $this->endSection() ?>