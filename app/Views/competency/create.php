<!-- File: app/Views/competency/create.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Competency</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Create Competency</h1>
        <form action="<?= base_url('/competency/store') ?>" method="post">
            <div class="mb-3">
                <label for="intCompetencyID" class="form-label">Competency ID</label>
                <input type="number" name="intCompetencyID" id="intCompetencyID" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="intJobTitleID" class="form-label">Job Title ID</label>
                <input type="number" name="intJobTitleID" id="intJobTitleID" class="form-control" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="bitActive" id="bitActive" class="form-check-input" checked>
                <label for="bitActive" class="form-check-label">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>