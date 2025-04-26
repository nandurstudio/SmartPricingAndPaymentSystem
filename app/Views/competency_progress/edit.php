<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Competency Progress</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Competency Progress</h2>
        <form action="<?= site_url('competencyprogress/update/' . $progress['id']) ?>" method="post">
            <div class="mb-3">
                <label for="achieved" class="form-label">Achieved</label>
                <select name="achieved" class="form-select" required>
                    <option value="1" <?= $progress['achieved'] ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= !$progress['achieved'] ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= site_url('competencyprogress') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>