<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<h1>List User Job Titles</h1>
<div id="liveAlertPlaceholder"></div>
<a href="<?= base_url('transactions/user_jobtitle/create') ?>" class="btn btn-primary mb-3">Add New</a>
<table id="user_jobtitle" class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Job Titles</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!-- Modal Edit user_jobtitle -->
<div class="modal fade" id="editUserJobTitleModal" tabindex="-1" aria-labelledby="editUserJobTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserJobTitleModalLabel">Edit User Job Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserJobTitleForm">
                    <div class="mb-3">
                        <label for="userJobTitleId" class="form-label">Job Title ID</label>
                        <input type="number" class="form-control" id="userJobTitleId" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="intUserID" class="form-label">User ID</label>
                        <input type="number" class="form-control" id="intUserID" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="userName" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="userName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jobTitle" class="form-label">Job Title</label>
                        <input type="text" class="form-control" id="jobTitle" readonly>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="achieved">
                        <label class="form-check-label" for="achieved">Achieved</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active">
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>