<!-- views/functional_competency/details.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>

<h2 class="mb-3"><?= esc($pageTitle) ?></h2>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= esc($competency['txtCompetency']) ?></h5>
        <p class="card-text"><?= esc($competency['txtDefinition']) ?></p>

        <a href="<?= base_url('/functionalcompetency/edit/' . $competency['id']) ?>" class="btn btn-warning">Edit</a>
        <a href="<?= base_url('/functionalcompetency') ?>" class="btn btn-secondary">Back to list</a>
    </div>
</div>

<?= $this->endSection() ?>