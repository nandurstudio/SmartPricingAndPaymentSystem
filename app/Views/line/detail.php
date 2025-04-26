<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Line Detail</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Line Detail</h1>
        <a href="<?php echo base_url('/line'); ?>" class="btn btn-secondary mb-3">Back to List</a>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= esc($line['txtLine']) ?></h5>
                <p class="card-text"><strong>Description:</strong> <?= esc($line['txtDesc']) ?></p>
                <p class="card-text"><strong>Status:</strong> <?= $line['bitActive'] ? 'Active' : 'Inactive' ?></p>
                <p class="card-text"><strong>GUID:</strong> <?= esc($line['txtGUID']) ?></p>
                <p class="card-text"><strong>Inserted By:</strong> <?= esc($line['txtInsertedBy']) ?></p>
                <p class="card-text"><strong>Updated By:</strong> <?= esc($line['txtUpdatedBy']) ?></p>
                <p class="card-text"><strong>Inserted Date:</strong> <?= esc($line['dtmInsertedDate']) ?></p>
                <p class="card-text"><strong>Updated Date:</strong> <?= esc($line['dtmUpdatedDate']) ?></p>
            </div>
        </div>
    </div>
</body>

</html>