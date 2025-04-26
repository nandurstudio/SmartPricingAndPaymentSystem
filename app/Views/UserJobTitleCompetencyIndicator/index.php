<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h2>User Job Title Competency Indicators</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>User Name</th>
                <th>Job Title</th>
                <th>Competency</th>
                <th>Indicator</th>
                <th>Achieved</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($indicators as $index => $indicator): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= $indicator['txtFullName']; ?></td>
                    <td><?= $indicator['txtJobTitle']; ?></td>
                    <td><?= $indicator['txtCompetency']; ?></td>
                    <td><?= $indicator['txtIndicator']; ?></td>
                    <td><?= $indicator['bitAchieved'] ? 'Yes' : 'No'; ?></td>
                    <td><?= $indicator['bitActive'] ? 'Yes' : 'No'; ?></td>
                    <td>
                        <a href="<?= base_url('UserJobTitleCompetencyIndicator/view/' . $indicator['intUserJobTitleCompetencyIndicatorID']); ?>" class="btn btn-info btn-sm">View</a>
                        <a href="<?= base_url('UserJobTitleCompetencyIndicator/edit/' . $indicator['intUserJobTitleCompetencyIndicatorID']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php
                        $deleteUrl = base_url('UserJobTitleCompetencyIndicator/delete/' . $indicator['intUserJobTitleCompetencyIndicatorID']);
                        ?>
                        <form action="<?= $deleteUrl; ?>" method="POST" style="display:inline;">
                            <?= csrf_field(); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this indicator?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Delete URL: <?= $deleteUrl; ?></p>
    <a href="<?= base_url('UserJobTitleCompetencyIndicator/create'); ?>" class="btn btn-primary">Add New Indicator</a>
</div>

<?= $this->endSection(); ?>