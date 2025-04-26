<!-- functional_competency/index.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>

<a href="<?= base_url('/functionalcompetency/create') ?>" class="btn btn-primary mb-3">Add New Functional Competency</a>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Competency</th>
            <th>Definition</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($functionalCompetencies)): ?>
            <?php foreach ($functionalCompetencies as $competency): ?>
                <tr>
                    <td><?= esc($competency['intFunctionalCompetencyID']) ?></td>
                    <td><?= esc($competency['txtCompetency']) ?></td>
                    <td><?= esc($competency['txtDefinition']) ?></td>
                    <td>
                        <?= $competency['bitActive'] ? 'Active' : 'Non-Active' ?>
                    </td>
                    <td>
                        <a href="<?= base_url('/functionalcompetency/view/' . $competency['intFunctionalCompetencyID']) ?>" class="btn btn-info btn-sm">View</a>
                        <a href="<?= base_url('/functionalcompetency/edit/' . $competency['intFunctionalCompetencyID']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No functional competencies found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>