<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Job Title</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap-icons/font/bootstrap-icons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/npm/@docsearch/css@3.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Job Title Details</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">ID: <?= $jobTitle['intJobTitleID']; ?></h5>
                <p class="card-text">Job Title: <?= $jobTitle['txtJobTitle']; ?></p>
                <p class="card-text">Inserted By: <?= $jobTitle['txtInsertedBy']; ?></p>
                <p class="card-text">Inserted Date: <?= $jobTitle['dtmInsertedDate']; ?></p>
                <p class="card-text">Updated By: <?= $jobTitle['txtUpdatedBy']; ?></p>
                <p class="card-text">Updated Date: <?= $jobTitle['dtmUpdatedDate']; ?></p>
                <a href="/job_title" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>