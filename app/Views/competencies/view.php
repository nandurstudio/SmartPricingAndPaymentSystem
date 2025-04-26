<!-- File: app/Views/competencies/view.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competency Detail</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Competency Detail</h1>
        <table class="table table-bordered">
            <tr>
                <th>Competency ID</th>
                <td><?= $competency['intCompetencyID']; ?></td>
            </tr>
            <tr>
                <th>Competency</th>
                <td><?= $competency['txtCompetency']; ?></td>
            </tr>
            <tr>
                <th>Definition</th>
                <td><?= $competency['txtDefinition']; ?></td>
            </tr>
            <tr>
                <th>Active</th>
                <td><?= $competency['bitActive'] ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
                <th>Inserted By</th>
                <td><?= $competency['txtInsertedBy']; ?></td>
            </tr>
            <tr>
                <th>Inserted Date</th>
                <td><?= $competency['dtmInsertedDate']; ?></td>
            </tr>
            <tr>
                <th>Updated By</th>
                <td><?= $competency['txtUpdatedBy']; ?></td>
            </tr>
            <tr>
                <th>Updated Date</th>
                <td><?= $competency['dtmUpdatedDate']; ?></td>
            </tr>
        </table>

        <a href="<?= base_url('competencies'); ?>" class="btn btn-secondary">Back to List</a>
        <a href="<?= base_url('competencies/edit/' . $competency['intCompetencyID']); ?>" class="btn btn-warning">Edit Competency</a>
    </div>

    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>