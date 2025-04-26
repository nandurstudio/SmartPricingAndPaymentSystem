<?= $this->extend('layouts/starter/main'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <h2>Indicator Details</h2>
    <p><strong>User:</strong> <?= $indicator['intUserID']; ?></p>
    <p><strong>Job Title:</strong> <?= $indicator['intJobTitleID']; ?></p>
    <p><strong>Competency:</strong> <?= $indicator['intCompetencyID']; ?></p>
    <p><strong>Indicator:</strong> <?= $indicator['intIndicatorID']; ?></p>
    <p><strong>Achieved:</strong> <?= $indicator['bitAchieved'] ? 'Yes' : 'No'; ?></p>
</div>
<a href="<?= base_url('/UserJobTitleCompetencyIndicator') ?>" class="btn btn-secondary">Cancel</a>

<?= $this->endSection(); ?>