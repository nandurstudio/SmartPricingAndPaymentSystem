<!-- index.php -->
<?= $this->extend('layouts/starter/main') ?>
<?= $this->section('content') ?>
<a href="<?php echo base_url('/line/create'); ?>" class="btn btn-primary mb-3">Add Line</a>
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Line Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lines as $line): ?>
            <tr>
                <td><?= esc($line['intLineID']) ?></td>
                <td><?= esc($line['txtLine']) ?></td>
                <td><?= esc($line['txtDesc']) ?></td>
                <td><?= $line['bitActive'] ? 'Active' : 'Inactive' ?></td>
                <td>
                    <a href="<?php echo base_url('/line/detail/' . $line['intLineID']); ?>" class="btn btn-info btn-sm">Detail</a>
                    <a href="<?php echo base_url('/line/edit/' . $line['intLineID']); ?>" class="btn btn-warning btn-sm">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>