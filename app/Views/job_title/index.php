<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Titles</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/npm/@docsearch/css@3.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Job Titles</h1>
        <a href="/job_title/create" class="btn btn-primary mb-3">Add New Job Title</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Job Title</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobTitles as $jobTitle): ?>
                    <tr>
                        <td><?= $jobTitle['intJobTitleID']; ?></td>
                        <td><?= $jobTitle['txtJobTitle']; ?></td>
                        <td><?= $jobTitle['bitActive'] ? 'Active' : 'Inactive' ?></td> <!-- Memperbaiki status menjadi teks -->
                        <td>
                            <a href="/job_title/edit/<?= $jobTitle['intJobTitleID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/job_title/view/<?= $jobTitle['intJobTitleID']; ?>" class="btn btn-info btn-sm">Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>