<?= $this->extend('layouts/starter/main') ?>

<?= $this->section('content') ?>
<h1><?= $pageTitle ?></h1>

<h2>Achieved Job Titles</h2>
<?php if (count($achieved) > 0): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Achieved</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($achieved as $item): ?>
                <tr>
                    <td><?= $item['txtJobTitle'] ?></td>
                    <td><?= $item['bitAchieved'] ? 'Yes' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No achieved job titles.</p>
<?php endif; ?>

<h2>Not Achieved Job Titles</h2>
<?php if (count($notAchieved) > 0): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Achieved</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notAchieved as $item): ?>
                <tr>
                    <td><?= $item['txtJobTitle'] ?></td>
                    <td><?= $item['bitAchieved'] ? 'Yes' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No not achieved job titles.</p>
<?php endif; ?>


<a href="<?= base_url('transactions/user_jobtitle') ?>" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Back
</a>

<?= $this->endSection() ?>