<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parameter List</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h2>Parameter List</h2>
        <a href="/" class="btn btn-secondary mb-3">Back to Dashboard</a> <!-- Tombol Back to Dashboard -->
        <a href="/parameter/create" class="btn btn-primary mb-3">Add New Parameter</a>
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
                <?php foreach ($parameters as $parameter): ?>
                    <tr>
                        <td><?= $parameter['txtParameterName'] ?></td>
                        <td><?= $parameter['txtParameterDesc'] ?></td>
                        <td><?= $parameter['bitActive'] ? 'Active' : 'Inactive' ?></td> <!-- Memperbaiki status menjadi teks -->
                        <td>
                            <a href="/parameter/edit/<?= $parameter['intParameterID'] ?>" class="btn btn-warning">Edit</a>
                            <a href="/parameter/view/<?= $parameter['intParameterID'] ?>" class="btn btn-success">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>