<!-- File: app/Views/competency/edit.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Competency</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Edit Competency</h1>
        <!-- Menambahkan intCompetencyID dan intJobTitleID di URL -->
        <form action="<?= base_url('/competency/update/' . $competency['intCompetencyID'] . '/' . $competency['intJobTitleID']) ?>" method="post">
            <!-- Competency Information -->
            <div class="mb-3">
                <label for="intCompetencyID" class="form-label">Competency ID</label>
                <input type="text" name="intCompetencyID" id="intCompetencyID" class="form-control" value="<?= $competency['intCompetencyID']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="intJobTitleID" class="form-label">Job Title ID</label>
                <input type="text" name="intJobTitleID" id="intJobTitleID" class="form-control" value="<?= $competency['intJobTitleID']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="bitActive" class="form-check-label">Active</label>
                <!-- Checkbox untuk status aktif -->
                <input type="checkbox" name="bitActive" id="bitActive" class="form-check-input" <?= $competency['bitActive'] ? 'checked' : ''; ?>>
            </div>

            <!-- Tombol Update -->
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>