<!-- File: app/Views/competency/view.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Competency</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h2>Competency Details</h2>
        <p><strong>Competency ID:</strong> <?= $competency['intCompetencyID']; ?></p>
        <p><strong>Job Title ID:</strong> <?= $competency['intJobTitleID']; ?></p>
        <p><strong>Active:</strong> <?= $competency['bitActive'] ? 'Yes' : 'No'; ?></p>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>