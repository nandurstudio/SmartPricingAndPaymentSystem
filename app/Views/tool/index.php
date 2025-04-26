<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tool List</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h2>Tool List</h2>
        <a href="/" class="btn btn-secondary mb-3">Back to Dashboard</a> <!-- Tombol Back to Dashboard -->
        <a href="/tool/create" class="btn btn-primary mb-3">Add New Tool</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tools as $tool): ?>
                    <tr>
                        <td><?= $tool['txtToolName'] ?></td>
                        <td><?= $tool['txtToolDesc'] ?></td>
                        <td><?= $tool['bitActive'] ? 'Active' : 'Inactive' ?></td> <!-- Memperbaiki status menjadi teks -->
                        <td>
                            <a href="/tool/edit/<?= $tool['intToolID'] ?>" class="btn btn-warning">Edit</a>
                            <a href="/tool/view/<?= $tool['intToolID'] ?>" class="btn btn-success">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>