<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Competency</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h1>Add Competency</h1>
        <form action="<?= base_url('competencies/store'); ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="active" class="form-check-label">Active</label>
                <input type="checkbox" name="active" id="active" class="form-check-input" checked>
            </div>
            <button type="submit" class="btn btn-success">Add Competency</button>
        </form>
    </div>
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>