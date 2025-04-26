<!-- views/functional_competency/edit.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>

<h2 class="mb-3"><?= esc($pageTitle) ?></h2>

<form action="<?= base_url('/functionalcompetency/update/' . $competency['id']) ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">

    <div class="mb-3">
        <label for="txtCompetency" class="form-label">Competency Name</label>
        <input type="text" class="form-control" id="txtCompetency" name="txtCompetency" value="<?= esc($competency['txtCompetency']) ?>" required>
        <?php if (isset($errors['txtCompetency'])): ?>
            <div class="text-danger"><?= esc($errors['txtCompetency']) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="txtDefinition" class="form-label">Competency Definition</label>
        <textarea class="form-control" id="txtDefinition" name="txtDefinition" rows="3" required><?= esc($competency['txtDefinition']) ?></textarea>
        <?php if (isset($errors['txtDefinition'])): ?>
            <div class="text-danger"><?= esc($errors['txtDefinition']) ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('/functionalcompetency') ?>" class="btn btn-secondary">Cancel</a>
</form>

<?= $this->endSection() ?>