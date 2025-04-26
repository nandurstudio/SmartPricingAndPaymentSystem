<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Competency</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Edit Competency</h1>
        <form action="<?= base_url('competencies/update/' . $competency['intCompetencyID']); ?>" method="POST">
            <input type="hidden" name="_method" value="PATCH">
            <div class="mb-3">
                <label for="txtCompetency" class="form-label">Competency Name</label>
                <input type="text" class="form-control" id="txtCompetency" name="txtCompetency" value="<?= old('txtCompetency', $competency['txtCompetency']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="txtDefinition" class="form-label">Definition</label>
                <textarea class="form-control" id="txtDefinition" name="txtDefinition" required><?= old('txtDefinition', $competency['txtDefinition']); ?></textarea>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="bitActive" name="bitActive" value="1" <?= $competency['bitActive'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="bitActive">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Update Competency</button>
            <a href="<?= base_url('competencies'); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>