<!-- index.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>

<a href="<?= base_url('/assessment/create') ?>" class="btn btn-primary mb-3">Add New Assessment</a>

<table id="assessmentTable" class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Line</th>
            <th>Job Title</th>
            <th>Competency</th>
            <th>Indicator</th>
            <th>Result</th>
            <th>Assessed Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($assessments as $assessment): ?>
            <tr>
                <td><?= $assessment['intAssessmentID'] ?></td>
                <td><?= $assessment['intUserID'] ?></td>
                <td><?= $assessment['intLineID'] ?></td>
                <td><?= $assessment['intJobTitleID'] ?></td>
                <td><?= $assessment['intCompetencyID'] ?></td>
                <td><?= $assessment['intIndicatorID'] ?></td>
                <td><?= $assessment['bitResult'] ? 'Pass' : 'Fail' ?></td>
                <td><?= $assessment['dtmAssessedDate'] ?></td>
                <td>
                    <a href="/assessment/view/<?= $assessment['intAssessmentID'] ?>" class="btn btn-info btn-sm">View</a>
                    <a href="/assessment/edit/<?= $assessment['intAssessmentID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="/assessment/delete/<?= $assessment['intAssessmentID'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>